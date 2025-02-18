<?php

declare(strict_types=1);

namespace App\Tests\Common\TestCase;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    protected function getService(string $name): object
    {
        return self::getContainer()->get($name);
    }
}
