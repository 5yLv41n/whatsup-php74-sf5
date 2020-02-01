<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public static function getGroups(): array
    {
        return ['users'];
    }

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User('niavlys95@gmail.com', ['ROLE_ADMIN']);
        $password = $this->passwordEncoder->encodePassword($user, 'p4ssW0rd');
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
