<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\MicroPost;
use App\Entity\User;

class AppFixtures extends Fixture
{
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
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $microPost = new MicroPost();
            $microPost->setText('Some random text ' . rand(0, 100));
            $microPost->setTime(new \DateTime('2019-07-24'));
            $microPost->setUser($this->getReference('alex_1'));
            $manager->persist($microPost);
        }
        
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('alex');
        $user->setFullName('Alexander');
        $user->setEmail('alex@test.com');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user, 
                '1111'
            )
        );

        $this->addReference('alex_1', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
