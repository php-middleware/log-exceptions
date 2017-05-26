<?php

namespace PhpMiddlewareTestTest\RequestId;

use Gamez\Psr\Log\TestLogger;
use PhpMiddleware\LogException\LogExceptionMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class LogExceptionMiddlewareTest extends TestCase
{
    private $logger;
    private $middleware;

    protected function setUp()
    {
        $this->logger = new TestLogger();
        $this->middleware = new LogExceptionMiddleware($this->logger);
    }

    public function testWorksAsItWhenNoException()
    {
        $next = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $result = $this->callMiddleware($next);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testLogNothingWhenNoException()
    {
        $next = function (ServerRequestInterface $request, ResponseInterface $response) {
            return $response;
        };

        $this->callMiddleware($next);

        $this->assertCount(0, $this->logger->getRecords());
    }

    public function testReThrowExceptionWhenNextThowException()
    {
        $next = function (ServerRequestInterface $request, ResponseInterface $response) {
            throw new RuntimeException();
        };

        $this->expectException(RuntimeException::class);

        $this->callMiddleware($next);
    }

    public function testLogExceptionWhenNextThowException()
    {
        $next = function (ServerRequestInterface $request, ResponseInterface $response) {
            throw new RuntimeException();
        };

        try {
            $this->callMiddleware($next);
        } catch (\Exception $e) {
        }

        $this->assertCount(1, $this->logger->getRecords());
    }

    private function callMiddleware(callable $next)
    {
        $request = new ServerRequest();
        $response = new Response();

        return call_user_func($this->middleware, $request, $response, $next);
    }
}
