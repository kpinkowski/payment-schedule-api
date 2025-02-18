<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CalculatePaymentScheduleRequest;
use App\Messenger\Query\GetPaymentScheduleQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
#[Route('/api/v1/schedule')]
final class ScheduleController
{
    use HandleTrait;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        MessageBusInterface $bus
    ) {
        $this->messageBus = $bus;
    }

    #[Route('/generate', name: 'api_generate_schedule', methods: ['POST'])]
    public function generate(Request $request): JsonResponse
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

    #[Route('/{paymentScheduleId}', name: 'api_get_schedule', methods: ['GET'])]
    public function getSchedule(int $paymentScheduleId): JsonResponse
    {
        try {
            $schedule = $this->handle(new GetPaymentScheduleQuery($paymentScheduleId));

            return new JsonResponse($schedule);
        } catch (\Throwable $th) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }
    }
}
