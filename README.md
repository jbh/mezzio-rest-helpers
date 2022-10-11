# Helpers for building RESTful handlers in Mezzio

Helpers and routes to help building a RESTful & HAL-compliant API in [Mezzio](https://github.com/mezzio/mezzio).

## Installation

Install this library using composer:

```
$ composer require sobored/mezzio-rest-helpers
```

## RestDispatchTrait

Use this trait for an easy way to create RESTful responses:

```php
<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Hal\HalResponseFactory;
use Mezzio\Hal\ResourceGenerator;
use App\Entity\UserEntity;
use App\TableGateway\UserTableGateway;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SoBoRed\Mezzio\Rest\Exception\NoResourceFoundException;
use SoBoRed\Mezzio\Rest\RestDispatchTrait;

class GetUserHandler implements RequestHandlerInterface
{
    /**
     * @var UserTableGateway
     */
    private $userTable;

    /**
     * Use REST Helper RestDispatchTrait to gain
     * $this->resourceGenerator, $this->responseFactory,
     * and $this->createResponse()
     */
    use RestDispatchTrait;

    public function __construct(
        UserTableGateway $userTable,
        ResourceGenerator $resourceGenerator,
        HalResponseFactory $responseFactory
    )
    {
        $this->userTable = $userTable;
        $this->resourceGenerator = $resourceGenerator;
        $this->responseFactory = $responseFactory;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $id = $request->getAttribute('id', false);
        $user = $this->userTable->get((int)$id)->current();

        if (! $user instanceof UserEntity) {
            // Throw a REST Helper Exception for HAL-compliant problem details in response
            throw NoResourceFoundException::create("User with id `{$id}` not found");
        }

        // Return a HAL-compliant response that contains the record requested
        return $this->createResponse($request, $user);
    }
}
```

## Exceptions

SoBoRed/mezzio-rest-helpers comes with helpful exceptions that use
[mezzio/mezzio-problem-details](https://github.com/mezzio/mezzio-problem-details)
to produce human-readable responses for API errors. An example
response for `NoResourceFoundException`, used above, would be:

```json
{
  "status": 404,
  "detail": "User with id `22` not found",
  "type": "/api/doc/resource-not-found",
  "title": "Resource not found"
}
```

#### Available exceptions

- Status 400: InvalidParameterException, OutOfBoundsException
- Status 405: MethodNotAllowedException
- Status 404: NoResourceFoundException
- Status 500: RuntimeException

## Docs

For the problem details to be HAL-compliant, they should point
to some form of documentation that helps the user understand the
problem they are encountering. For this reason, mezzio-rest-helpers
provides documentation and routes for it, which each exception's
type points to.

#### Available doc routes

- /api/doc/invalid-parameter
- /api/doc/method-not-allowed-error
- /api/doc/resource-not-found
- /api/doc/parameter-out-of-range
- /api/doc/runtime-error
