<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SnapController extends AbstractController {

	/**
  * @Route("/plant-snap", name="app_plant_snap")
  */
  public function read(): Response
  {
	    return $this->render('front/snap/view.html.twig', [
          
      ]);
  }

}