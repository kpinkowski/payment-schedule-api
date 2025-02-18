<?php

declare(strict_types=1);

namespace App\Tests\Application\Controller;

use App\Enum\Currency;
use App\Enum\ProductType;
use App\Tests\Common\TestCase\ApiTestCase;

final class ScheduleControllerTest extends ApiTestCase
{
    public function testItGeneratesAndFetchesPaymentSchedule(): void
    {
        $token = $this->getToken();
        $authHeader = ['HTTP_AUTHORIZATION' => "Bearer $token"];

        $payload = [
            'productName' => 'someProduct',
            'productPrice' => [
                'amount' => 1000,
                'currency' => Currency::USD->value
            ],
            'productDateSold' => '2025-01-01T00:00:00+00:00',
            'productType' => ProductType::ELECTRONICS->value
        ];

        $this->client->request(
            'POST',
            '/api/v1/schedule',
            [],
            [],
            $authHeader,
            json_encode($payload)
        );

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertResponseHasHeader('Location');

        $scheduleUrl = $this->client->getResponse()->headers->get('Location');

        self::assertNotNull($scheduleUrl, 'Location header should contain the schedule URL');

        $this->client->request('GET', $scheduleUrl, [], [], $authHeader);
        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');

        $responseData = json_decode(
            $this->client->getResponse()->getContent(),
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
        $authHeader = ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->getToken()];

        $this->client->request('GET', 'http://localhost/api/v1/schedule/999999', [], [], $authHeader);

        self::assertResponseStatusCodeSame(404);
    }
}
