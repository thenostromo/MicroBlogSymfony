<?php

namespace App\Event;

use App\Entity\UserPreferences;
use App\Mailer\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * UserSubscriber constructor.
     * @param Mailer $mailer
     * @param EntityManagerInterface $entityManager
     * @param string $defaultLocale
     */
    public function __construct(
        Mailer $mailer,
        EntityManagerInterface $entityManager,
        string $defaultLocale
    ) {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister'
        ];
    }

    /**
     * @param UserRegisterEvent $event
     */
    public function onUserRegister(UserRegisterEvent $event)
    {
        $preferences = new UserPreferences();
        $preferences->setLocale('en');

        $user = $event->getRegisteredUser();
        $user->setPreferences($preferences);

        $this->entityManager->flush();

        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }
}
