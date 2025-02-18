<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Psr\Log\LoggerInterface;

final class RequestLoggerListener
{
    private float $startTime;

    public function __construct(private readonly LoggerInterface $httpRequestLogger)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $this->startTime = microtime(true);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $response = $event->getResponse();

        $duration = (microtime(true) - $this->startTime) * 1000;

        $this->httpRequestLogger->info('Request', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'status' => $response->getStatusCode(),
            'duration' => $duration,
            'ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
            'body' => $request->getContent(),
        ]);
    }
}
