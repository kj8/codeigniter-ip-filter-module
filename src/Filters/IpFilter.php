<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Filters;

use CodeIgniter\Config\Factories;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Kj8\Module\IpFilter\Config\IpFilter as IpFilterConfig;
use Kj8\Module\IpFilter\Config\Services;

class IpFilter implements FilterInterface
{
    public const NAME = 'kj8_ipfilter';

    /**
     * @return RequestInterface|ResponseInterface|string|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        /**
         * @var IpFilterConfig $config
         */
        $config = Factories::config('IpFilter');

        // nazwa zestawu IP (np. admin, api)
        $setName = $arguments[0] ?? 'default';

        if (!isset($config->sets[$setName])) {
            throw new \RuntimeException("IpFilter: no configuration for set '{$setName}'");
        }

        $set = $config->sets[$setName];

        $ip = $request->getIPAddress();
        $mode = $set['mode'];
        $ips = $set['ips'];

        if (
            (IpFilterConfig::MODE_ALLOW === $mode && !\in_array($ip, $ips, true))
            || (IpFilterConfig::MODE_DENY === $mode && \in_array($ip, $ips, true))
        ) {
            return Services::kj8IpFilterRequestTypeResolver()
                ->resolve($request)
                ->respond();
        }

        return null;
    }

    /**
     * @return ResponseInterface|null
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
