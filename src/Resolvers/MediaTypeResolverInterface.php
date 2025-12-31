<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Resolvers;

use CodeIgniter\HTTP\RequestInterface;

interface MediaTypeResolverInterface
{
    public function resolve(RequestInterface $request): ?string;
}
