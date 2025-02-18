<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixture extends Fixture
{
    public const ADMIN_EMAIL = 'admin@example.com';
    public const ADMIN_PASSWORD = 'password';

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            self::ADMIN_EMAIL,
            [UserRole::ADMIN->value]
        );

        $hashedPassword = $this->passwordHasher->hashPassword($user, self::ADMIN_PASSWORD);
        $user->setPassword($hashedPassword);

        $manager->persist($user);
        $manager->flush();
    }
}
