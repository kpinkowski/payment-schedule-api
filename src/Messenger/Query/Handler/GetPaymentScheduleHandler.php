<?php

declare(strict_types=1);

namespace App\Messenger\Query\Handler;

use App\Dto\PaymentScheduleResponse;
use App\Factory\PaymentScheduleResponseFactory;
use App\Messenger\Query\GetPaymentScheduleQuery;
use App\Repository\PaymentScheduleRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'message.bus')]
final class GetPaymentScheduleHandler
{
    private const LOG_TAG = '[GetPaymentScheduleHandler]: ';
    private const ERROR_LOG = self::LOG_TAG . 'Error occurred during processing query.';

    public function __construct(
        private readonly PaymentScheduleRepository $repository,
        private readonly PaymentScheduleResponseFactory $responseFactory,
        private readonly LoggerInterface $appLogger
    ) {
    }

    public function __invoke(GetPaymentScheduleQuery $query): PaymentScheduleResponse
    {
        try {
            $schedule = $this->repository->getSchedule($query->getPaymentScheduleId());

            if ($schedule === null) {
                throw new NotFoundHttpException('Payment schedule not found');
            }

            return $this->responseFactory->create($schedule);
        } catch (NotFoundHttpException $e) {
            $this->appLogger->error(self::ERROR_LOG, ['exception' => $e->getMessage()]);
            throw $e;
        }
    }
}
