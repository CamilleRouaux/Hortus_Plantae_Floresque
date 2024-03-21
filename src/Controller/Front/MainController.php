<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function home(): Response
    {
        return $this->render('front/main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/contact", name="app_contact_us")
     */
    public function contactUs(): Response
    {
        return $this->render('front/main/contact.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/legal-notice", name="app_legal_notice")
     */
    public function legalNotice(): Response
    {
        return $this->render('front/main/legal.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/test", name="app_test")
     */
    public function test(): Response
    {
        return $this->render('test.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
