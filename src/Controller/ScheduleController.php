<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CalculatePaymentScheduleRequest;
use App\Dto\PaymentScheduleResponse;
use App\Messenger\Query\GetPaymentScheduleQuery;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[AsController]
#[Route('/api/v1/schedule')]
final class ScheduleController
{
    use HandleTrait;

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        MessageBusInterface $bus
    ) {
        $this->messageBus = $bus;
    }

    #[OA\Post(
        description: "Creates a new payment schedule based on the provided product data.",
        summary: "Creates a new payment schedule",
        requestBody: new OA\RequestBody(
            required: true,
            content: new Model(type: CalculatePaymentScheduleRequest::class)
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Payment schedule successfully created",
                headers: [
                    new OA\Header(
                        header: "Location",
                        description: "The URL of the created payment schedule",
                        schema: new OA\Schema(type: "string")
                    )
                ],
                content: new Model(type: PaymentScheduleResponse::class)
            ),
            new OA\Response(
                response: 400,
                description: "Invalid request data",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Invalid product type")
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: "Access denied"
            )
        ]
    )]
    #[Route('', name: 'api_create_schedule', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CalculatePaymentScheduleRequest::class, 'json');

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $schedule = $this->handle($dto->toCommand());

        return new JsonResponse(
            null,
            Response::HTTP_CREATED,
            ['Location' => "/api/v1/schedule/{$schedule->getId()}"]
        );
    }

    #[OA\Get(
        description: "Gets details of a payment schedule by its ID.",
        summary: "Retrieve a payment schedule",
        parameters: [
            new OA\Parameter(
                name: "paymentScheduleId",
                description: "ID of the payment schedule",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 404,
                description: "Payment schedule not found"
            ),
            new OA\Response(
                response: 403,
                description: "Access denied"
            )
        ]
    )]
    #[Route('/{paymentScheduleId}', name: 'api_get_schedule', methods: ['GET'])]
    public function getSchedule(int $paymentScheduleId): JsonResponse
    {
        try {
            $schedule = $this->handle(new GetPaymentScheduleQuery($paymentScheduleId));

            return new JsonResponse($schedule);
        } catch (Throwable $e) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }
    }
}
