<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class GenerateScheduleRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $productId;
}
