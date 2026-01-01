<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Services;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Kj8\Module\IpFilter\Config\Services;
use Kj8\Module\IpFilter\Providers\IpSetProviderInterface;

class IpFilterService
{
    public function __construct(
        private readonly IpSetProviderInterface $provider,
        private readonly IpChecker $checker,
    ) {
    }

    /**
     * @param array<int, string>|null $arguments
     */
    public function handle(RequestInterface $request, ?array $arguments): ?ResponseInterface
    {
        $ip = $request->getIPAddress();
        $setName = $arguments[0] ?? 'default';

        $set = $this->provider->getSet($setName);

        if ($this->checker->isBlocked($set['mode'], $ip, $set['ips'])) {
            return Services::kj8IpFilterRequestTypeResolver()
                ->resolve($request)
                ->respond();
        }

        return null;
    }
}
