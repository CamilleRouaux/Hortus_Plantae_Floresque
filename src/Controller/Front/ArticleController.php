<?php

namespace App\Controller\Front;

use App\Entity\Notification;
use App\Entity\User;
use App\Repository\NotificationRepository;
use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\Front\CommentType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    /**
    * @Route("/", name="app_article_carousel")
    */
    public function listOfLastTreeArticle(ArticleRepository $articleRepository): Response
    {
        $articleList = $articleRepository->findLastTreeArticleByType();

        return $this->render('front/main/home.html.twig', [
            'articleList' => $articleList,
        ]);
    }

    /**
     * @Route("/article/list", name="app_article_list")
     */
    public function listOfArticle(ArticleRepository $articleRepository): Response
    {
        $articleList = $articleRepository->findArticleByType();

        return $this->render('front/article/list.html.twig', [
            'articleList' => $articleList,
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_by_id", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function readAnArticle($id, ArticleRepository $articleRepository, NotificationRepository $notificationRepository): Response
    {
        $article = $articleRepository->find($id);
        $loggedInUser = $this->getUser();
        $notifications = [];

        if ($article === null) {
            throw $this->createNotFoundException('Article non trouvé !');
        }

        $comments = $article->getComment();
        if ($loggedInUser) {
            foreach ($comments as $comment) {
                $user = $comment->getUser();
                $notification = $notificationRepository->findBandRequestNotification($loggedInUser, $user);
                if ($notification) {
                    $notifications[] = $notification;
                }
            }
        }

        return $this->render('front/article/article.html.twig', [
            'article' => $article,
            'notifications' => $notifications,
        ]);
    }

    /**
     * @Route("/card/list", name="app_card_list")
     */
    public function listOfCard(ArticleRepository $articleRepository): Response
    {
        $cardList = $articleRepository->findCardByType();

        return $this->render('front/card/list.html.twig', [
            'cardList' => $cardList,
        ]);
    }

    /**
     * @Route("/card/{id}", name="card_by_id", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function readACard($id, ArticleRepository $articleRepository, NotificationRepository $notificationRepository): Response
    {
        $card = $articleRepository->find($id);
        $loggedInUser = $this->getUser();
        $notifications = [];

        if ($card === null) {
            throw $this->createNotFoundException('Fiche conseil non trouvée !');
        }

        $comments = $card->getComment();
        if ($loggedInUser) {
            foreach ($comments as $comment) {
                $user = $comment->getUser();
                $notification = $notificationRepository->findBandRequestNotification($loggedInUser, $user);
                if ($notification) {
                    $notifications[] = $notification;
                }
            }
        }

        return $this->render('front/card/card.html.twig', [
            'card' => $card,
            'notifications' => $notifications,
        ]);
    }

    /**
     * @Route("/article/{id}/comment", name="article_by_id_add_comment", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function addCommentOnArticle(Article $article, Request $request, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();

        $comment->setCreatedAt(new DateTimeImmutable());
        $comment->setUser($this->getUser());

        $comment->setArticle($article);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire à été ajouté');

            return $this->redirectToRoute('article_by_id', ["id" => $article->getId()]);
        }

        return $this->renderForm('front/article/comment.html.twig', [
            'form' => $form,
            'article' => $article,
        ]);
    }

    /**
     * @Route("/card/{id}/comment", name="card_by_id_add_comment", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function addCommentOnCard(Article $article, Request $request, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();

        $comment->setCreatedAt(new DateTimeImmutable());
        $comment->setUser($this->getUser());

        $comment->setArticle($article);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire à été ajouté');

            return $this->redirectToRoute('article_by_id', ["id" => $article->getId()]);
        }

        return $this->renderForm('front/article/comment.html.twig', [
            'form' => $form,
            'article' => $article,
        ]);
    }

}

