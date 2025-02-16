<?php

namespace App\Api\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated) {}

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $paths = $openApi->getPaths();

        $paths->addPath('/api/v1/schedule/generate', new PathItem(
            post: new Operation(
                operationId: 'generateSchedule',
                tags: ['Schedule'],
                responses: [
                    '200' => new Model\Response(
                        'Schedule generated',
                        new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string', 'example' => 'Payment schedule generated']
                                    ]
                                ]
                            ]
                        ])
                    ),
                    '400' => new Model\Response(
                        'Invalid request',
                        new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'error' => ['type' => 'string', 'example' => 'Invalid productId']
                                    ]
                                ]
                            ]
                        ])
                    ),
                    '404' => new Model\Response(
                        'Product not found',
                        new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'error' => ['type' => 'string', 'example' => 'Product not found']
                                    ]
                                ]
                            ]
                        ])
                    )
                ],
                summary: 'Generates a payment schedule for a product',
                requestBody: new Model\RequestBody(
                    'Payment schedule request',
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'productId' => [
                                        'type' => 'integer',
                                        'example' => 42
                                    ]
                                ],
                                'required' => ['productId']
                            ]
                        ]
                    ])
                )
            )
        ));

        $paths->addPath('/api/v1/schedule/{scheduleId}', new PathItem(
            get: new Operation(
                operationId: 'getSchedule',
                tags: ['Schedule'],
                responses: [
                    '200' => new Model\Response(
                        'Payment schedule',
                        new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'amount' => ['type' => 'number'],
                                            'currency' => ['type' => 'string'],
                                            'dueDate' => ['type' => 'string', 'format' => 'date-time']
                                        ]
                                    ]
                                ]
                            ]
                        ])
                    )
                ],
                summary: 'Gets a payment schedule for a product'
            )
        ));
        return $openApi->withPaths($paths);
    }
}
