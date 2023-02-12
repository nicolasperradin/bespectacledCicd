<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setUsername('TestUser');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password123'));
        $user->setRoles(['ROLE_USER']);
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTime());
        $manager->persist($user);

        $user = new User();
        $user->setEmail('test2@example.com');
        $user->setUsername('TestUser2');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password123'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTime());
        $manager->persist($user);

        $manager->flush();
    }
}