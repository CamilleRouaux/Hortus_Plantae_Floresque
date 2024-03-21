<?php

namespace App\Controller\Back;

use App\Entity\Notification;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/back/notification", name="app_back_notification_")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list(NotificationRepository $notificationRepository): Response
    {
        $notificationList = $notificationRepository->findAll();

        return $this->render('back/notification/list.html.twig', [
            'notificationList' => $notificationList,
        ]);
    }

    /**
     * @Route("/{id}", name="view")
     */
    public function view(NotificationRepository $notificationRepository, $id): Response
    {
        $notification = $notificationRepository->find($id);

        return $this->render('back/notification/view.html.twig', [
            'notification' => $notification,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"POST"})
     */
    public function delete(Notification $notification): Response
    {
        // You may want to add some authorization checks here to ensure
        // that only authorized users can delete notifications.

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($notification);
        $entityManager->flush();

        $this->addFlash('success', 'Notification deleted successfully.');

        // You can redirect to the notification list or any other page
        return $this->redirectToRoute('app_back_notification_list');
    }

}
