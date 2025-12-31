<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Resolvers;

use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;

class Ci4MediaTypeResolver implements MediaTypeResolverInterface
{
    /**
     * @param array<int, string> $supportedMediaTypes
     */
    public function __construct(
        private readonly array $supportedMediaTypes,
    ) {
    }

    public function resolve(RequestInterface $request): ?string
    {
        return ($request instanceof IncomingRequest)
            ? $request->negotiate('media', $this->supportedMediaTypes)
            : null;
    }
}
