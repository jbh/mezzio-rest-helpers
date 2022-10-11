<?php

declare(strict_types=1);

namespace SoBoRed\Mezzio\Rest\Doc;

use Psr\Container\ContainerInterface;

class InvalidParameterHandlerFactory
{
    public function __invoke(ContainerInterface $container) : InvalidParameterHandler
    {
        return new InvalidParameterHandler();
    }
}
