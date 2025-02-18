<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\AuthDto;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthService
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository
    ) {
    }

    public function getJWT(AuthDto $authDto): ?string
    {
        $user = $this->userRepository->findUserByEmail($authDto->email);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $authDto->password)) {
            return null;
        }

        return $this->jwtManager->create($user);
    }
}
