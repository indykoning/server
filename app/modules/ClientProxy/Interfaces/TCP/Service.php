<?php

declare(strict_types=1);

namespace Modules\ClientProxy\Interfaces\TCP;

use Modules\VarDumper\Application\RequestHandler as VarDumperRequestHandler;
use Psr\Log\LoggerInterface;
use Spiral\RoadRunner\Tcp\Request;
use Spiral\RoadRunner\Tcp\TcpWorkerInterface;
use Spiral\RoadRunnerBridge\Tcp\Response\ContinueRead;
use Spiral\RoadRunnerBridge\Tcp\Response\ResponseInterface;
use Spiral\RoadRunnerBridge\Tcp\Service\ServiceInterface;

class Service implements ServiceInterface
{
    public function __construct(
        private readonly VarDumperRequestHandler $varDumper,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function handle(Request $request): ResponseInterface
    {
        if ($request->event === TcpWorkerInterface::EVENT_CONNECTED) {
            return new ContinueRead();
        }

        $messages = \json_decode($request->body, true, 512, JSON_THROW_ON_ERROR);

        foreach ($messages as $message) {
            try {
                $this->handlePayload($message);
            } catch (\Throwable $e) {
                $this->logger->debug($e->getMessage());
            }
        }

        return new ContinueRead();
    }

    /**
     * @param array{type: string, data: string, time: string} $payload
     */
    private function handlePayload(array $payload): void
    {
        match ($payload['type']) {
            'var-dumper' => $this->varDumper->handle($payload['data']),
            default => throw new \RuntimeException('Unknown type of payload'),
        };
    }
}
