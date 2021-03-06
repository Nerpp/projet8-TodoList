<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/user_index", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        if (!$this->isGranted('view_user', $this->getUser())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour visualiser cette page');
            return $this->redirectToRoute('default');
        }

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findBy(
                ['isVerified' => true]
            ),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$this->isGranted('create_user', $this->getUser())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour visualiser cette page');
            return $this->redirectToRoute('default');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isCsrfTokenValid('user_new', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setRoles($user->getRoles());
            $user->setIsVerified(1);
            $user->setPassword($this->encoder->hashPassword($user, $user->getPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur à bien été crée');
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        if (!$this->isGranted('view_user', $this->getUser())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour visualiser cette page');
            return $this->redirectToRoute('default');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        if (!$this->isGranted('edit_user', $this->getUser())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour visualiser cette page');
            return $this->redirectToRoute('default');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->isCsrfTokenValid('user_edit', $request->request->get('_token'))) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'utilisateur à bien été édité');
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if (!$this->isGranted('delete_user', $this->getUser())) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécéssaire pour utiliser cette option');
            return $this->redirectToRoute('default');
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur à bien été supprimé');
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
