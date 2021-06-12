<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $admin = new User();
        $user->setEmail('user@test.fr');
        $admin->setEmail('admin@test.fr');
        $user->setUsername('user');
        $admin->setUsername('admin');
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'user_password'
        ));
        $admin->setPassword($this->passwordHasher->hashPassword(
            $admin,
            'admin_password'
        ));
        $user->setRoles(['ROLE_USER']);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->persist($admin);
        $manager->flush();
    }
}
