<?php

namespace App\Controller\Front;

use App\Entity\Notification;
use App\Entity\User;
use App\Form\Front\UserEditType;
use App\Form\Front\UserPasswordType;
use App\Form\Front\UserType;
use App\Repository\CommentRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user", name="app_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="create", methods={"GET","POST"})
     */
    public function create(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->setStatus(true);
        $user->setRoles(['ROLE_MEMBER']);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $clearPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $clearPassword);
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Création de compte réalisée !');

            return $this->redirectToRoute('app_home');

        }

        return $this->renderForm('front/user/create.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(NotificationRepository $notificationRepository)
    {
        $user = $this->getUser();
        $favorites = $user->getFavorite();

        $roleChangeNotifications = $notificationRepository->findRoleChangeNotificationsForSender($user);

        $roleChangeRequested = !empty($roleChangeNotifications);


        return $this->render('front/user/profile.html.twig', [
            'user' => $user,
            'favorites' => $favorites, 
            'roleChangeRequested' => $roleChangeRequested,

        ]);
    }
    /**
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('warning', 'Profil modifié.');

            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('front/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/edit/password", name="edit_password")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('warning', 'Mot de passe modifié.');

            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('front/user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/request-role-change", name="request_role_change")
     */
    public function requestRoleChange(NotificationRepository $notificationRepository): Response
    {
        $loggedInUser = $this->getUser();

        if (!$loggedInUser) {
            return $this->redirectToRoute('app_loguser');
        }

        // Check if there's an existing unresolved role change request
        $roleChangeNotifications = $notificationRepository->findRoleChangeNotificationsForSender($loggedInUser);

        if ($roleChangeNotifications) {
            // A request is already pending, set a flash message
            $this->addFlash('warning', 'A role change request is already pending for your user.');
            return $this->redirectToRoute('app_user_profile');
        }

        $notification = new Notification();
        $notification->setType('role');
        $notification->setMessage('Requesting role change for ' . $loggedInUser->getUsername());
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setSender($loggedInUser);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($notification);
        $entityManager->flush();

        // Set a success flash message
        $this->addFlash('success', 'Role change request submitted successfully.');
        return $this->redirectToRoute('app_user_profile');
    }
    
        /**
     * @Route("/{userId}/{articleId}/{commentId}", name="request_ban")
     */
    public function requestBan(CommentRepository $commentRepository, UserRepository $userRepository, NotificationRepository $notificationRepository, $userId, $articleId, $commentId): Response
    {
        $loggedInUser = $this->getUser();
        $user = $userRepository->find($userId);



        if (!$loggedInUser) {
            return $this->redirectToRoute('app_loguser');
        }

        // Check if there's an existing unresolved ban request
        $existingRequest = $notificationRepository->findBandRequestNotification($loggedInUser, $user);

        if ($existingRequest) {
            // A request is already pending, set a flash message
            $this->addFlash('warning', 'A ban request is already pending for this user.');
            return $this->redirectToRoute('article_by_id', ["id" => $user->getId()]);
        }
        $comment = $commentRepository->find($commentId);

        $notification = new Notification();
        $notification->setType('banning');
        $notification->setMessage('Requesting ban on user ' . $user->getEmail() . ' for the following comment: ' . $comment->getContent());
        $notification->setCreatedAt(new \DateTimeImmutable());
        $notification->setBan($user);
        $notification->setSender($loggedInUser);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($notification);
        $entityManager->flush();

        $this->addFlash('success', 'Ban request submitted successfully.');

        return $this->redirectToRoute('article_by_id', ["id" => $articleId]);
    }
}
