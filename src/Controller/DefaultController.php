<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
 
    /**
     * @Route("/", name="default")
     * 
     */
    public function index(): Response
    {
       
        if (!$this->isGranted('browser_user',$this->getUser())) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'user' => new User,
        ]);
    }
}
