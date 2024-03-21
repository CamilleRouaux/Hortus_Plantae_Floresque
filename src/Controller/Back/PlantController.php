<?php

namespace App\Controller\Back;

use App\Entity\Picture;
use App\Form\Back\PictureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plant;
use App\Form\Back\PlantType;
use App\Repository\PlantRepository;
use App\Repository\TagRepository; /* ajouté par rapport à le version developp **/
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 *
 * @Route("/back/plant", name="app_back_plant_", methods={"GET"})
 */
class PlantController extends AbstractController
{
    /**
     * List page
     *
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(PlantRepository $plantRepository)
    {
        // get datas
        $allPlants = $plantRepository->findAllOrderBy();


        // send to view
        return $this->render('back/plant/list.html.twig', [
            'back_list' => $allPlants,
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response /* correction de createPlant en create **/
    {
        $plant = new Plant();
        $form = $this->createForm(PlantType::class, $plant);
        $tags = $form->get('tags')->getData(); // test

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // foreach ($form->get('tags')->getData() as $currentTag) {  /* ajouté par rapport à version developp **/
            //    $currentTag->addPlant($plant);  /* ajouté par rapport à version developp **/
            // }
            foreach ($tags as $currentTag) {
                $currentTag->addPlant($plant);
            }

            $em->persist($plant);
            $em->flush();
            $this->addFlash('success', 'Plante ajoutée!'); /* message "user ajouté" remplacé par "plante ajoutée" **/

            return $this->redirectToRoute('app_back_plant_list');

        }

        return $this->renderForm('back/plant/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * details of a plant
     *
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read(PlantRepository $plantRepository, $id)
    {
        // $plant = Plants::getPlantById($id);
        // get plant in db by id
        // $plant = $plantRepository->find($id);
        $plant = $plantRepository->find($id);

        // dd($plante);
        // try to get a value
        // doctrine makes the request
        // $plant->getPlants()[0]->getName();
        // dump($plant);
        // dd($plant);
        // verify if plant exists
        if ($plant === null) { /*décommenté pour remettre au même état que la version développ **/
            /*
                symfony makes a try catch, if plant doesn't exist it shows a 404 error
            */

            throw $this->createNotFoundException('Plante non trouvée! Voulez-vous créer une nouvelle fiche encyclo?'); /*décommenté pour remettre au même état que la version développ **/
        } /*décommenté pour remettre au même état que la version développ **/

        // send to view
        return $this->render('back/plant/view.html.twig', [
            'plant' => $plant,
        ]);
    }

    /**
     * @Route("/{id}/update", name="update", methods={"GET","POST"})
     */
    public function edit(Plant $plant, TagRepository $tagRepository, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PlantType::class, $plant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $allTag = $tagRepository->findAll();
            foreach ($allTag as $currentTag) {
                $currentTag->removePlant($plant);
            }
            foreach ($form->get('tags')->getData() as $currentTag) {
                $currentTag->addPlant($plant);
            }
            $em->persist($plant);
            $em->flush();
            $this->addFlash('warning', 'plante modifiée!');
            return $this->redirectToRoute('app_back_plant_list');
        }

        return $this->renderForm('back/plant/edit.html.twig', [
            'form' => $form,
            'plant' => $plant,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Plant $plant, PlantRepository $plantRepository): Response
    {
        $plantRepository->remove($plant, true);
        if ($this->isCsrfTokenValid('delete-plant-' . $plant->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Plante supprimée');
        }

        return $this->redirectToRoute('app_back_plant_list', [], Response::HTTP_SEE_OTHER);
    }




    /**
     * @Route("/{id}/picture/add", name="picture_add", methods={"GET", "POST"})
     */
    public function new(Request $request, SluggerInterface $slugger, $id)
    {
        $em = $this->getDoctrine()->getManager();

        // Assuming that $id is the ID of the associated plant
        $plant = $em->getRepository(Plant::class)->find($id);

        if (!$plant) {
            throw $this->createNotFoundException('Plant not found');
        }

        $picture = new Picture();
        $picture->setPlant($plant);

        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $plantPicture */
            $plantPicture = $form->get('picture')->getData();

            // Generate a unique filename for the uploaded file
            $originalFilename = pathinfo($plantPicture->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $plantPicture->guessExtension();

            // Move the file to the directory where pictures are stored
            try {
                $plantPicture->move(
                    $this->getParameter('uploads_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle exception if something happens during file upload
                throw $e;
            }

            // Set the plant for the picture
            $picture->setPlant($plant);
            // Set the filename in the Picture entity
            $picture->setUrl($newFilename);

            $em->persist($picture);
            $em->flush();

            return $this->redirectToRoute('app_back_plant_read', ['id' => $id]);
        }

        return $this->renderForm('back/plant/upload.html.twig', [
            'form' => $form,
            'plant' => $plant,
        ]);
    }


}