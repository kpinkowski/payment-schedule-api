<?php

declare(strict_types=1);

namespace App\Messenger\Query\Handler;

use App\Dto\PaymentScheduleResponse;
use App\Factory\PaymentScheduleResponseFactory;
use App\Messenger\Query\GetPaymentScheduleQuery;
use App\Repository\PaymentScheduleRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'message.bus')]
final class GetPaymentScheduleHandler
{
    public function __construct(
        private readonly PaymentScheduleRepository $repository,
        private readonly PaymentScheduleResponseFactory $responseFactory
    ) {
    }

    public function __invoke(GetPaymentScheduleQuery $query): PaymentScheduleResponse
    {
        $schedule = $this->repository->getSchedule($query->getPaymentScheduleId());

        if ($schedule === null) {
            throw new NotFoundHttpException('Payment schedule not found');
        }

        return $this->responseFactory->create($schedule);
    }
}
