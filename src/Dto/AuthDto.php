<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[OA\Schema(title: 'AuthDto')]
final class AuthDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[OA\Property(example: 'admin@example.com')]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[OA\Property(example: 'password')]
    public string $password;
}
