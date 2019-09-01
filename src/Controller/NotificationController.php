<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Notification;
use App\Manager\NotificationManager;
use App\Repository\NotificationRepository;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/unread-count", name="notification_unread")
     *
     * @param NotificationManager $notificationManager
     * @return JsonResponse
     */
    public function unreadCount(NotificationManager $notificationManager)
    {
        return new JsonResponse([
            'count' => $notificationManager->getUnseenByUser($this->getUser())
        ]);
    }

    /**
     * @Route("/all", name="notification_all")
     *
     * @param NotificationManager $notificationManager
     * @return Response
     */
    public function notifications(NotificationManager $notificationManager)
    {
        return $this->render(
            'notification/notifications.html.twig',
            [
                'notifications' => $notificationManager->getAllByUser($this->getUser())
            ]
        );
    }

    /**
     * @Route("/acknowledge/{id}", name="notification_acknowledge")
     *
     * @param Notification $notification
     * @param NotificationManager $notificationManager
     * @return RedirectResponse
     */
    public function acknowledge(Notification $notification, NotificationManager $notificationManager)
    {
        $notificationManager->makeSeen($notification);

        return $this->redirectToRoute('notification_all');
    }

    /**
     * @Route("/acknowledge-all", name="notification_acknowledge_all")
     *
     * @param NotificationManager $notificationManager
     * @return RedirectResponse
     */
    public function acknowledgeAll(NotificationManager $notificationManager)
    {
        $notificationManager->makeAllSeen($this->getUser());

        return $this->redirectToRoute('notification_all');
    }
}
