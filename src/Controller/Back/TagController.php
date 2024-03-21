<?php

namespace App\Controller\Back;

use App\Entity\Tag;
use App\Form\Back\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/back/tag", name="app_back_tag_")
 */
class TagController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     */
    public function list(TagRepository $tagRepository): Response
    {

        $tagList = $tagRepository->findAllOrderBy();

        return $this->render('back/tag/list.html.twig', [
            'tagList' => $tagList,
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', 'Tag ajouté avec succes !');

            return $this->redirectToRoute('app_back_tag_list');
        }

        return $this->render('back/tag/add.html.twig', [
            'formView' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="read", methods={"GET"})
     */
    public function read($id, TagRepository $tagRepository): Response
    {

        $tag = $tagRepository->find($id);

        if ($tag === null) {

            throw $this->createNotFoundException('Tag non trouvé !');
        }

        return $this->render('back/tag/read.html.twig', [
            'tag' => $tag,
        ]);
    }


    /**
     * @Route("/{id}/update", name="update", methods={"GET","POST"})
     */
    public function update(Tag $tag, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($tag);

            $em->flush();

            $this->addFlash('warning', 'Tag modifié');

            return $this->redirectToRoute('app_back_tag_list');
        }

        return $this->render('back/tag/update.html.twig', [
            'formView' => $form->createView(),
            'tag' => $tag,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function delete(Tag $tag, TagRepository $tagRepository): Response
    {

        $tagRepository->remove($tag, true);
        $this->addFlash('danger', 'Tag supprimé');

        return $this->redirectToRoute('app_back_tag_list', [],Response::HTTP_SEE_OTHER);
    }

}
