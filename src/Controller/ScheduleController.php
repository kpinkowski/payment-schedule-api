<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CalculatePaymentScheduleDto;
use App\Entity\Product;
use App\Factory\CalculatePaymentScheduleCommandFactory;
use App\Messenger\Command\CalculatePaymentScheduleCommand;
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
        private readonly MessageBusInterface $commandBus,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {}

    #[Route('/generate', name: 'api_generate_schedule', methods: ['POST'])]
    public function generate(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), CalculatePaymentScheduleDto::class, 'json');

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $product = $this->entityManager->getRepository(Product::class)->find($dto->productId);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_BAD_REQUEST);
        }

        $this->commandBus->dispatch($dto->toCommand());

        // TODO: Return the generated schedule ID in headers
        return new JsonResponse(['message' => 'Payment schedule generated'], Response::HTTP_CREATED);
    }

    #[Route('/{$paymentScheduleId}', name: 'api_get_schedule', methods: ['GET'])]
    public function getSchedule(int $paymentScheduleId): JsonResponse
    {
        $schedules = $this->handle(new GetPaymentScheduleQuery($paymentScheduleId));

        return new JsonResponse($schedules);
    }
}
