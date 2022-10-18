<?php

declare(strict_types=1);

namespace SoBoRed\Mezzio\Rest;

use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use Mezzio\Hal\ResourceGenerator\Exception\OutOfBoundsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait RestDispatchTrait
{
    /**
     * @var ResourceGenerator
     */
    private $resourceGenerator;

    /**
     * @var HalResponseFactory
     */
    private $responseFactory;

    /**
     * Create a HAL response from the given $instance, based on the incoming $request.
     *
     * @param ServerRequestInterface $request
     * @param object $instance
     * @return ResponseInterface
     */
    private function createResponse(ServerRequestInterface $request, object $instance): ResponseInterface
    {
        try {
            return $this->responseFactory->createResponse(
                $request,
                $this->resourceGenerator->fromObject($instance, $request)
            );
        } catch (OutOfBoundsException $e) {
            /**
             * Conveniently catch out-of-bound pages for pagination
             *
             * This is not a catch-all. Be sure to check and throw
             * relevant exceptions (NoResourceFoundException,
             * InvalidParameterException, etc.) inside whatever
             * uses this trait.
             */
            throw Exception\OutOfBoundsException::create($e->getMessage());
        }
    }
}
