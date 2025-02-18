<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

final class UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }
}
