<?php

namespace App\Controller\Back;

use App\Repository\TagRepository;
use App\Repository\PlantRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back", name="app_back_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ArticleRepository $articleRepository, TagRepository $tagRepository, PlantRepository $plantRepository): Response
    {

        $articleListForBackHome = $articleRepository->findArticlesForBackHome();
        $tagListForBackHome = $tagRepository->findTagsForBackHome();
        $plantListForBackHome = $plantRepository->findPlantsForBackHome();

        return $this->render('back/main/home.html.twig', [
            'articleListForBackHome' => $articleListForBackHome,
            'tagListForBackHome' => $tagListForBackHome,
            'plantListForBackHome' => $plantListForBackHome,
        ]);
    }
}

