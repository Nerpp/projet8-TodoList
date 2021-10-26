<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistrationController extends AbstractController
{
    

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(MailerInterface $mailer,Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRoles($user->getRoles());
            $user->setPassword(
                $encoder->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            
            $hash = md5(random_int(100000, 9999999));
            $user->setTokenValidation($hash);
            $time = new \DateTime('+2 days');
            $user->setAskedAt($time);

            // generate a signed url and email it to the user
            // $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            //     (new TemplatedEmail())
            //         ->from(new Address('smptpserveur@gmail.com', 'TODO-Bot'))
            //         ->to($user->getEmail())
            //         ->subject('Please Confirm your Email')
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );
            // do anything else you need here, like send an email

            $email = (new TemplatedEmail())
                ->from('smptpserveur@gmail.com')
                ->to($user->getEmail())
                ->subject('Thanks for signing up!')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context([
                    'expiration_date' => $time,
                    'displayName' => $form->get('displayName')->getData(),
                    'hash' => $hash,
                ]);

                try {
                    $mailer->send($email);
                }
                
         // @codeCoverageIgnoreStart
                catch (TransportExceptionInterface $e) {
                  
                    $this->addFlash('failed', 'Un probléme est survenue lors de l\'envoit du mail, veuillez vous réinscrire !');
                    return $this->redirectToRoute('app_register');
                }
         // @codeCoverageIgnoreEnd
      

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email/{token}", name="app_verify_email")
     */
    public function verifyUserEmail($token,Request $request, UserRepository $users): Response
    {
        
        $user = $users->findOneBy(['token_validation' => $token]);

         
         // @codeCoverageIgnoreStart
        if (!$user) {
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Unknow user');
        }
         // @codeCoverageIgnoreEnd

        $now = new \DateTime();
        $interval = $now->diff($user->getAskedAt());
        $interval = (int)$interval->format('%R%a');

         // @codeCoverageIgnoreStart
        if ($interval >> 2) {
            throw $this->createNotFoundException('Bloody fate, the confirmation is out of concern, can you try again !');
        }
        // @codeCoverageIgnoreEnd

        $user->setIsVerified(true);
        $user->setTokenValidation(null);
        $user->setAskedAt(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        // validate email confirmation link, sets User::isVerified=true and persists
        // try {
        //     dd('test');
        //     $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        // } catch (VerifyEmailExceptionInterface $exception) {
        //     $this->addFlash('verify_email_error', $exception->getReason());

        //     return $this->redirectToRoute('app_register');
        // }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre email à bien été verifié.');

        return $this->redirectToRoute('default');
    }


}
