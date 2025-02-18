<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Enum\Currency;
use App\Enum\ProductType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ScheduleControllerTest extends WebTestCase
{
    public function testItGeneratesAndFetchesPaymentSchedule(): void
    {
        $client = static::createClient();

        $payload = [
            'productName' => 'someProduct',
            'productPrice' => [
                'amount' => 1000,
                'currency' => Currency::USD->value
            ],
            'productDateSold' => '2021-01-01',
            'productType' => ProductType::ELECTRONICS->value
        ];

        $client->request(
            'POST',
            '/api/v1/schedule/generate',
            [],
            [],
            [],
            json_encode($payload)
        );

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertResponseHasHeader('Location');

        $scheduleUrl = $client->getResponse()->headers->get('Location');

        self::assertNotNull($scheduleUrl, 'Location header should contain the schedule URL');

        $client->request('GET', $scheduleUrl);
        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode(
            $client->getResponse()->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertArrayHasKey('schedule', $responseData);
        $this->assertIsArray($responseData['schedule']);

        foreach ($responseData['schedule'] as $item) {
            $this->assertArrayHasKey('amount', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('dueDate', $item);
            $this->assertIsInt($item['amount']);
            $this->assertIsString($item['currency']);
            $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}/', $item['dueDate']);
        }
    }

    public function testItReturns404CodeWhenScheduleIsNotFound(): void
    {
        $client = static::createClient();

        $client->request('GET', 'http://localhost/api/v1/schedule/999999');

        self::assertResponseStatusCodeSame(404);
    }
}
