<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/404", name="app_notfound")
     */
    public function notFound(): Response
    {
        return $this->render('error404.html.twig', [
            'controller_name' => 'ErrorController',
        ]);
    }

     /**
     * @Route("/403", name="app_forbidden")
     */
    public function forbidden (): Response
    {
        return $this->render('error403.html.twig', [
            'controller_name' => 'ErrorController',
        ]);
    } }