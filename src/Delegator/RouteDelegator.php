<?php

declare(strict_types=1);

namespace SoBoRed\Mezzio\Rest\Delegator;

use SoBoRed\Mezzio\Rest\Doc\InvalidParameterHandler;
use SoBoRed\Mezzio\Rest\Doc\MethodNotAllowedHandler;
use SoBoRed\Mezzio\Rest\Doc\OutOfBoundsHandler;
use SoBoRed\Mezzio\Rest\Doc\ResourceNotFoundHandler;
use SoBoRed\Mezzio\Rest\Doc\RuntimeErrorHandler;
use Mezzio\Application;
use Psr\Container\ContainerInterface;

class RouteDelegator
{
    public function __invoke(ContainerInterface $container, string $serviceName, callable $factory): Application
    {
        /** @var Application $application */
        $app = $factory();

        $app->get('/api/doc/invalid-parameter', InvalidParameterHandler::class);
        $app->get('/api/doc/method-not-allowed-error', MethodNotAllowedHandler::class);
        $app->get('/api/doc/resource-not-found', ResourceNotFoundHandler::class);
        $app->get('/api/doc/parameter-out-of-range', OutOfBoundsHandler::class);
        $app->get('/api/doc/runtime-error', RuntimeErrorHandler::class);

        return $application;
    }
}
