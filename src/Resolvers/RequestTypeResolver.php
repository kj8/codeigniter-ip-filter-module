<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Resolvers;

use CodeIgniter\HTTP\RequestInterface;
use Kj8\Module\IpFilter\Exceptions\UnsupportedMediaTypeException;
use Kj8\Module\IpFilter\Responders\ResponderInterface;

final class RequestTypeResolver
{
    /**
     * @param array<string, class-string<ResponderInterface>> $responders
     */
    public function __construct(
        private readonly array $responders,
        private readonly MediaTypeResolverInterface $mediaTypeResolver,
    ) {
    }

    public function resolve(RequestInterface $request): ResponderInterface
    {
        $mediaType = $this->mediaTypeResolver->resolve($request);

        if (null !== $mediaType && isset($this->responders[$mediaType])) {
            return new $this->responders[$mediaType]();
        }

        throw new UnsupportedMediaTypeException(\sprintf('No responder configured for media type "%s".', $mediaType ?? 'unknown'));
    }
}
