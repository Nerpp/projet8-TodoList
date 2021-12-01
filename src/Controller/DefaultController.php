<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {

        $this->security = $security;
    }
    /**
     * @Route("/", name="default")
     * 
     */
    public function index(): Response
    {
        if (!$this->isGranted('view_user',$this->security->getUser())) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'user' => new User,
        ]);
    }
}
