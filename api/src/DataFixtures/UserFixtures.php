<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_US');

        // Admin
        $admin = (new User())
            ->setUserName($faker->name)
            ->setEmail('root1@root.com')
            ->setRoles(['ROLE_ADMIN']);

        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));

        $manager->persist($admin);
        $this->setReference('user_1', $admin);

        // User
        $user = (new User())
            ->setUserName($faker->name)
            ->setEmail('root2@root.com');

        $user->setPassword($this->hasher->hashPassword($user, 'password'));

        $manager->persist($user);
        $this->setReference('user_2', $user);

        $manager->flush();
    }
}
