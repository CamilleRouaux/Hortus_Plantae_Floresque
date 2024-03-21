<?php

namespace App\Controller\Back;

use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\Back\ArticleType;
use App\Repository\TagRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back", name="app_back_")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/article/list", name="article_list")
     */
    public function listOfArticle(ArticleRepository $articleRepository): Response
    {
        $articleList = $articleRepository->findAllOrderBy();

        return $this->render('back/article/list.html.twig', [
            'articleList' => $articleList,
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_by_id", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function readAnArticle($id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        if ($article === null) {

            throw $this->createNotFoundException('Article non trouvé !');
        }

        return $this->render('back/article/article.html.twig', [
            'article' => $article,
        ]);
    }


    /**
     * @Route("/card/{id}", name="card_by_id", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function readACard($id, ArticleRepository $articleRepository): Response
    {
        $card = $articleRepository->find($id);

        if ($card === null) {

            throw $this->createNotFoundException('Fiche conseil non trouvée !');
        }

        return $this->render('back/article/card.html.twig', [
            'card' => $card,
        ]);
    }

    /**
     * @Route("/article/add", name="article_add", methods={"GET","POST"})
     */
    public function createArticle(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();

        $article->setCreatedAt(new DateTimeImmutable());
        $article->setUser($this->getUser());

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('tags')->getData() as $currentTag) {
                $currentTag->addArticle($article);
            }
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Article ajouté !');

            return $this->redirectToRoute('app_back_article_list');
        }

        return $this->renderForm('back/article/add.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/article/{id}/edit", name="article_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function updateArticle(TagRepository $tagRepository, Request $request, Article $article, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        $article->setUpdatedAt(new DateTimeImmutable());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $allTag = $tagRepository->findAll();
            foreach ($allTag as $currentTag) {
                $currentTag->removeArticle($article);
            }
            foreach ($form->get('tags')->getData() as $currentTag) {
                $currentTag->addArticle($article);
            }

            $em->persist($article);
            $em->flush();

            $this->addFlash('warning', 'Article modifié !');

            return $this->redirectToRoute('app_back_article_list');

        }

        return $this->renderForm('back/article/edit.html.twig', [
            'form' => $form,
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article/{id}/delete", name="article_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function deleteArticle(Article $article, Request $request, ArticleRepository $articleRepository): Response
    {
        $articleRepository->remove($article, true);
        $this->addFlash('danger', 'Article supprimé');
        if ($this->isCsrfTokenValid('delete-article-' . $article->getId(), $request->request->get('_token'))) {
        }

        return $this->redirectToRoute('app_back_article_list');
    }

    /**
     * @Route("/article/{id}/comment/delete", name="article_comment_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function deleteComment(Comment $comment, Request $request, CommentRepository $commentRepository): Response
    {
        $commentRepository->remove($comment, true);
        $this->addFlash('danger', 'Commentaire supprimé');
        if ($this->isCsrfTokenValid('delete-comment-' . $comment->getId(), $request->request->get('_token'))) {
        }

        return $this->redirectToRoute('app_back_article_by_id', ["id" => $comment->getArticle()->getId()], Response::HTTP_SEE_OTHER);
    }
}
