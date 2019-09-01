<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;

class MicroPostManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MicroPostRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->repository = $entityManager->getRepository(MicroPost::class);
    }

    /**
     * @param PersistentCollection $users
     * @return MicroPost[]
     */
    public function getMicroPostByUsers(PersistentCollection $users)
    {
        return $this->repository->findAllByUsers($users);
    }

    /**
     * @param User $user
     * @return MicroPost[]
     */
    public function getAllByUser(User $user)
    {
        return $this->repository->findBy(
            [
                'user' => $user
            ],
            [
                'time' => 'DESC'
            ]
        );
    }

    /**
     * @return MicroPost[]
     */
    public function getAll()
    {
        return $this->repository->findBy(
            [],
            ['time' => 'DESC']
        );
    }

    /**
     * @param MicroPost $microPost
     */
    public function update(MicroPost $microPost)
    {
        $this->entityManager->persist($microPost);
        $this->entityManager->flush();
    }

    /**
     * @param MicroPost $microPost
     */
    public function delete(MicroPost $microPost)
    {
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();
    }
}
