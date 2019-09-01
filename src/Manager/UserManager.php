<?php

namespace App\Manager;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Event\UserRegisterEvent;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\TokenGenerator;

class UserManager
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGenerator $tokenGenerator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenGenerator = $tokenGenerator;
        $this->eventDispatcher = $eventDispatcher;

        $this->repository = $entityManager->getRepository(User::class);
    }

    /**
     * @param User $currentUser
     * @param array $posts
     * @return array|User[]
     */
    public function getUsersToFollow(User $currentUser, array $posts)
    {
        return (count($posts) === 0)
            ? $this->repository->findAllWithMoreThan5PostsExceptUser($currentUser)
            : [];
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function getUserByConfirmToken($token)
    {
        return $this->repository->findOneBy([
            'confirmationToken' => $token
        ]);
    }

    /**
     * @param User $user
     */
    public function activeUser(User $user)
    {
        $user->setEnabled(true);
        $user->setConfirmationToken('');

        $this->entityManager->flush();
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     */
    public function register(User $user)
    {
        $password = $this->passwordEncoder->encodePassword(
            $user,
            $user->getPlainPassword()
        );

        $user->setPassword($password);
        $user->setConfirmationToken($this->tokenGenerator->getRandomSecureToken(30));

        $this->updateUser($user);

        $userRegisterEvent = new UserRegisterEvent($user);
        $this->eventDispatcher->dispatch(
            UserRegisterEvent::NAME,
            $userRegisterEvent
        );
    }
}
