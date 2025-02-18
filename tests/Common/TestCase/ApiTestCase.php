<?php

declare(strict_types=1);

namespace App\Tests\Common\TestCase;

use App\DataFixtures\UserFixture;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApiTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    protected function getService(string $name): object
    {
        return self::getContainer()->get($name);
    }

    protected function getToken(): string
    {
        $payload = [
            'email' => UserFixture::ADMIN_EMAIL,
            'password' => UserFixture::ADMIN_PASSWORD
        ];

        $this->client->request('POST', '/login', [], [], [], json_encode($payload));

        $response = json_decode($this->client->getResponse()->getContent(), true);

        return $response['token'];
    }
}
