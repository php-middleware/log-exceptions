<?php

namespace PhpMiddleware\LogException;

use Exception;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PhpMiddleware\DoublePassCompatibilityTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final class LogExceptionMiddleware implements MiddlewareInterface
{
    use DoublePassCompatibilityTrait;

    const DEFAULT_LOG_MESSAGE = 'An exception has been caught in middleware';

    private $logger;
    private $level;
    private $message;

    public function __construct(LoggerInterface $logger, $level = LogLevel::CRITICAL, $message = self::DEFAULT_LOG_MESSAGE)
    {
        $this->logger = $logger;
        $this->level = $level;
        $this->message = $message;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        try {
            return $delegate->process($request);
        } catch (Exception $exception) {
            $this->logger->log($this->level, $this->message, [
                'exception' => (string) $exception,
            ]);
            throw $exception;
        }
    }
}
