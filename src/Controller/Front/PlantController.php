<?php

namespace App\Controller\Front;

use App\Entity\Favorite;
use App\Repository\FavoriteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plant;
use App\Repository\PlantRepository;

/**
 * @Route("/plant", name="app_front_plant_")
 */
class PlantController extends AbstractController
{
    /**
     * List page
     *
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(PlantRepository $plantRepository, Request $request)
    {
        // Get the selected letter from the query parameters
        $selectedLetter = $request->query->get('letter');

        // Fetch and sort all plants based on the selected letter
        $plantList = $plantRepository->findByLetter($selectedLetter);

        // Check if the plant list is empty
        if (empty($plantList)) {
            // Create a flash message
            $this->addFlash('warning', 'Aucune plante trouvée dans notre base de données');

            //!! this redirection created a loop when plant table is empthy because it will infinitely redirect you to a empthy page. this weas the error found to the crash.
            //* return $this->redirectToRoute('app_front_plant_list', ['letter' => $selectedLetter]);
        }

        $userFavorites = [];
        if ($this->getUser() !== null) {
            $favorites = $this->getUser()->getFavorite();
            foreach ($favorites as $favorite) {
                $userFavorites[] = $favorite->getPlant();
            }
        }

        // Send to view
        return $this->render('front/plant/list.html.twig', [
            'plant_list' => $plantList,
            'userFavorites' => $userFavorites,
        ]);
    }



    /**
     * Details page of a plant
     *
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function read(PlantRepository $plantRepository, $id, FavoriteRepository $favoriteRepository)
    {

        $plant = $plantRepository->find($id);

        if ($plant === null) {
            // return $this->redirectToRoute('app_front_plant_list');
        }
        // Determine if the plant is a favorite for the currently logged-in user
        $loggedInUser = $this->getUser();
        $isFavorite = false;

        if ($loggedInUser) {
            $favorite = $favoriteRepository->findOneBy(['user' => $loggedInUser, 'plant' => $plant]);
            $isFavorite = $favorite !== null;
        }

        // Send to view
        return $this->render('front/plant/view.html.twig', [
            'plant' => $plant,
            'isFavorite' => $isFavorite,
        ]);
    }


    /**
     * @Route("/{id}/favorite-toggle", name="favorite_toggle")
     */
    public function favoriteToggle(Plant $plant, FavoriteRepository $favoriteRepository): Response
    {
        $loggedInUser = $this->getUser();

        if (!$loggedInUser) {
            return $this->redirectToRoute('app_loguser');
        }

        // Check if there's an existing favorite for this user and plant
        $existingFavorite = $favoriteRepository->findOneBy(['user' => $loggedInUser, 'plant' => $plant]);

        $entityManager = $this->getDoctrine()->getManager();

        if ($existingFavorite) {
            // If a favorite exists, remove it
            $entityManager->remove($existingFavorite);
            $entityManager->flush();

            $this->addFlash('danger', 'Plante retirée de vos favoris');

            return $this->redirectToRoute('app_front_plant_show', ["id" => $plant->getId()]);
        } else {
            // If no favorite exists, add a new favorite
            $favorite = new Favorite();
            $favorite->setPlant($plant);
            $favorite->setUser($loggedInUser);

            $entityManager->persist($favorite);
            $entityManager->flush();

            $this->addFlash('success', 'Plante ajoutée à vos favoris !');

            return $this->redirectToRoute('app_front_plant_show', ["id" => $plant->getId()]);
        }
    }

    // Méthode à implémenter plus tard pour rajouter un bouton favoris toggle sur chaque plante dans la liste des plantes 
    /** 
     * @Route("/{id}/favorite-toggle-list", name="favorite_toggle_list")
     */
    public function favoriteToggleList(Plant $plant, FavoriteRepository $favoriteRepository): Response
    {
        $loggedInUser = $this->getUser();

        if (!$loggedInUser) {
            return $this->redirectToRoute('app_loguser');
        }

        // Check if there's an existing favorite for this user and plant
        $existingFavorite = $favoriteRepository->findOneBy(['user' => $loggedInUser, 'plant' => $plant]);

        $entityManager = $this->getDoctrine()->getManager();

        if ($existingFavorite) {
            // If a favorite exists, remove it
            $entityManager->remove($existingFavorite);
            $entityManager->flush();

            $this->addFlash('danger', 'Plante retirée de vos favoris');

            return $this->redirectToRoute('app_front_plant_list');
        } else {
            // If no favorite exists, add a new favorite
            $favorite = new Favorite();
            $favorite->setPlant($plant);
            $favorite->setUser($loggedInUser);

            $entityManager->persist($favorite);
            $entityManager->flush();

            $this->addFlash('success', 'Plante ajoutée à vos favoris !');

            return $this->redirectToRoute('app_front_plant_list');
        }
    }



}
