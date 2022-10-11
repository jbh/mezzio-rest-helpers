<?php

declare(strict_types=1);

namespace SoBoRed\Mezzio\Rest\Doc;

use Psr\Container\ContainerInterface;

class ResourceNotFoundHandlerFactory
{
    public function __invoke(ContainerInterface $container) : ResourceNotFoundHandler
    {
        return new ResourceNotFoundHandler();
    }
}
