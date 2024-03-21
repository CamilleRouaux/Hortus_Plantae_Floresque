<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use App\Repository\PlantRepository;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * Search action
     *
     * @Route("/search", name="search_bar", methods={"GET"})
     */
    public function search(Request $request, PlantRepository $plantRepository, ArticleRepository $articleRepository, TagRepository $tagRepository)
    {
        $searchTerm = $request->query->get('search');

        if (!$searchTerm) {
            // Handle the case where the search term is not provided
            $this->addFlash('warning', 'Please provide a search term.');

            return $this->redirectToRoute('app_home');
        }

        $plants = $plantRepository->searchPlants($searchTerm);
        $articles = $articleRepository->searchArticles($searchTerm, 'article');
        $cards = $articleRepository->searchArticles($searchTerm, 'fiche');
        $tagResults = $tagRepository->searchByTag($searchTerm);

        // Merge the results with the previous ones
        $plants = array_merge($plants, $tagResults['plants']);
        $articles = array_merge($articles, $tagResults['articles']);

        return $this->render('front/main/search_results.html.twig', [
            'plants' => $plants,
            'articles' => $articles,
            'cards' => $cards,
            'searchTerm' => $searchTerm,
        ]);
    }

}
