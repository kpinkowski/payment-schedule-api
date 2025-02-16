<?php

declare(strict_types=1);

namespace App\Tests\Common;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestCase extends KernelTestCase
{
    public function getService(string $name): object
    {
        return self::getContainer()->get($name);
    }
}
