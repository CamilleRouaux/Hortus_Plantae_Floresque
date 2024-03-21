<?php

namespace App\Controller\Back;

use App\Entity\Notification;
use App\Entity\User;
use App\Form\Back\UserType;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/user", name="app_back_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list(UserRepository $userRepository, NotificationRepository $notificationRepository): Response
    {
        // Retrieve a list of users from the repository.
        $userList = $userRepository->findAll();
        $notifications = $notificationRepository->findAll();
        // foreach ($userList as $user) {   
        //     dump( $user->getEmail());
        // }
        // exit;

        return $this->render('back/user/list.html.twig', [
            'userList' => $userList,
            'notifications' => $notifications,
        ]);
    }

    /**
     * @Route("/{id}", name="view")
     */
    public function view(UserRepository $userRepository, $id): Response
    {
        // Find a user by their ID using the repository.
        $user = $userRepository->find($id);

        // Render the template with the user's details.
        return $this->render('back/user/view.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, $id, UserRepository $userRepository, NotificationRepository $notificationRepository)
    {
        // Find a user by ID using the repository.
        $user = $userRepository->find($id);
        $notificationList = $notificationRepository->findAll();

        // Check if the user exists; if not, throw an exception.
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }
        $conditionsMet = false;

        // Create a form for editing the user's profile using the UserType form.
        $form = $this->createForm(UserType::class, $user);
        foreach ($notificationList as $notification) {
            if ($notification->getType() == 'banning' && $id == $notification->getban()->getId()) {
                $conditionsMet = true;
                $form->add('status', ChoiceType::class, [
                    'label' => 'Status',
                    'choices' => [
                        'Active' => true,
                        'Inactive' => false,
                    ],
                ]);
                $this->get('session')->set('notification_id_to_delete', $notification->getId());
            } elseif ($user->isStatus() == false) {
                $form->add('status', ChoiceType::class, [
                    'label' => 'Status',
                    'choices' => [
                        'Active' => true,
                        'Inactive' => false,
                    ],
                ]);
            }
        }
        foreach ($notificationList as $notification) {
            if ($notification->getType() == 'role' && $id == $notification->getSender()->getId()) {
                $this->get('session')->set('notification_id_to_delete', $notification->getId());
            }
        }
        $form->handleRequest($request);

        // If the form is submitted and valid, update the user's profile.
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if user roles are empty and assign 'ROLE_MEMBER'
            if (empty($user->getRoles())) {
                $user->setRoles(['ROLE_MEMBER']);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Check if there is a notification to delete
            $notificationIdToDelete = $this->get('session')->get('notification_id_to_delete');

            if ($conditionsMet && $notificationIdToDelete) {
                // Delete the notification
                $notification = $notificationRepository->find($notificationIdToDelete);

                if ($notification) {
                    $entityManager->remove($notification);
                    $entityManager->flush();
                }

                // Clear the session variable
                $this->get('session')->remove('notification_id_to_delete');
            }

            $this->addFlash('warning', 'Profil modifié.');

            return $this->redirectToRoute('app_back_user_list');
        }

        // Render the template with the edit form and user details.
        return $this->render('back/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'conditionsMet' => $conditionsMet,
        ]);
    }

}