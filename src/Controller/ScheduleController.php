<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\GenerateScheduleRequest;
use App\Message\Query\GetPaymentScheduleQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\HandleTrait;
use App\Message\Command\CalculatePaymentScheduleCommand;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
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
        $dto = $this->serializer->deserialize($request->getContent(), GenerateScheduleRequest::class, 'json');

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse(['error' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        $product = $this->entityManager->getRepository(Product::class)->find($dto->productId);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_BAD_REQUEST);
        }

        $this->commandBus->dispatch(new CalculatePaymentScheduleCommand($product));

        return new JsonResponse(['message' => 'Payment schedule generated']);
    }

    #[Route('/{productId}', name: 'api_get_schedule', methods: ['GET'])]
    public function getSchedule(int $paymentScheduleId): JsonResponse
    {
        $schedules = $this->handle(new GetPaymentScheduleQuery($paymentScheduleId));

        return new JsonResponse($schedules);
    }
}
