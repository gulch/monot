<?php

namespace Monot\Handler;

use Psr\Log\LoggerInterface;
use League\BooBoo\Handler\HandlerInterface;

class MonotLogHandler implements HandlerInterface
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(\Exception $e): void
    {

        if ($e instanceof \ErrorException) {
            $this->handleErrorException($e);
            return;
        }

        $this->logger->critical($e->getMessage());
    }

    protected function handleErrorException(\ErrorException $e): void
    {
        switch ($e->getSeverity()) {

            case E_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_PARSE:
                $this->logger->error($e->getMessage());
                break;

            case E_WARNING:
            case E_USER_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                $this->logger->warning($e->getMessage());
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $this->logger->notice($e->getMessage());
                break;

            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $this->logger->info($e->getMessage());
                break;
        }
    }
}
