<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;

class NotificationManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var NotificationRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->repository = $entityManager->getRepository(Notification::class);
    }

    /**
     * @param User $user
     * @return Notification[]
     */
    public function getAllByUser(User $user)
    {
        return $this->repository->findBy([
            'seen' => false,
            'user' => $user
        ]);
    }

    /**
     * @param User $user
     * @return Notification[]
     */
    public function getUnseenByUser(User $user)
    {
        return $this->repository->findUnseenByUser($user);
    }

    /**
     * @param Notification $notification
     */
    public function makeSeen(Notification $notification)
    {
        $notification->setSeen(true);

        $this->entityManager->flush();
    }

    /**
     * @param User $user
     */
    public function makeAllSeen(User $user)
    {
        $this->repository->markAllAsReadByUser($user);

        $this->entityManager->flush();
    }
}
