<?php

namespace App\DataFixtures;

use App\Entity\UserPreferences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\MicroPost;
use App\Entity\User;

class AppFixtures extends Fixture
{
     const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
            'roles' => [User::ROLE_USER]
         ],
         [
             'username' => 'admin',
             'email' => 'admin@test.com',
             'password' => 'admin12345',
             'fullName' => 'Super Admin',
             'roles' => [User::ROLE_ADMIN]
         ],
    ];

     const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    const LANGUAGES = [
     'en',
     'fr'
    ];

    /**
     * @var UserPasswordEncoderInterface $passwordEncoder
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    private function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(
                self::POST_TEXT[
                    rand(
                        0,
                        (count(self::POST_TEXT) - 1)
                    )
                ]
            );
            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');
            $microPost->setTime($date);
            $microPost->setUser(
                $this->getReference(
                    self::USERS[
                        rand(
                            0,
                            (count(self::USERS) - 1)
                        )
                    ]['username']
                )
            );
            $manager->persist($microPost);
        }
        
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $userData['password']
                )
            );
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);

            $this->addReference($userData['username'], $user);

            $preferences = new UserPreferences();
            $preferences->setLocale(self::LANGUAGES[rand(0, 1)]);

            $user->setPreferences($preferences);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
