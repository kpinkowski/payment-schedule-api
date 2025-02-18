<?php

declare(strict_types=1);

namespace App\EventListener;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class ResponseTimeListener
{
    private float $startTime;
    private float $warningThreshold;
    private float $criticalThreshold;
    private CollectorRegistry $registry;

    public function __construct(
        private LoggerInterface $performanceLogger,
        ParameterBagInterface $params
    ) {
        $this->startTime = microtime(true);
        $this->warningThreshold = $params->get('app.response_time_warning') / 1000;
        $this->criticalThreshold = $params->get('app.response_time_critical') / 1000;
        $this->registry = new CollectorRegistry(new InMemory());
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $duration = (microtime(true) - $this->startTime) * 1000;
        $this->performanceLogger->info('Response time', ['duration' => $duration]);

        $histogram = $this->registry->getOrRegisterHistogram(
            'app',
            'response_time',
            'Response time in milliseconds',
            ['method']
        );

        $histogram->observe($duration, [$event->getRequest()->getMethod()]);

        if ($duration > (int) ($_ENV['RESPONSE_TIME_ERROR_THRESHOLD'] ?? 2000)) {
            $this->performanceLogger->error('Very slow response!', ['duration' => $duration]);
        } elseif ($duration > (int) ($_ENV['RESPONSE_TIME_WARNING_THRESHOLD'] ?? 500)) {
            $this->performanceLogger->warning('Slow response', ['duration' => $duration]);
        }
    }
}
