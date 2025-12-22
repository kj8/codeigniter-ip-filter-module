<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Filters;

use CodeIgniter\Config\Factories;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Kj8\Module\IpFilter\Config\IpFilter as IpFilterConfig;

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
            return $this->respond($request);
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

    private function respond(RequestInterface $request): ResponseInterface
    {
        // @phpstan-ignore function.notFound
        $title = lang('IpFilter.blockedTitle');

        // @phpstan-ignore function.notFound
        $message = lang('IpFilter.blockedMessage');

        /**
         * @var ResponseInterface $response
         */
        // @phpstan-ignore function.notFound
        $response = service('response');

        if (
            $request instanceof IncomingRequest && 'application/json' === $request->negotiate('media', ['application/json', 'text/html'])
        ) {
            return $response
                ->setStatusCode(403)
                ->setContentType('application/json')
                ->setJSON([
                    'status' => 403,
                    'error' => 403,
                    'messages' => ['error' => $message],
                ]);
        }

        // @phpstan-ignore function.notFound
        $view = view('Kj8\Module\IpFilter\Views\blocked', [
            'ip_filter_blocked_title' => $title,
            'ip_filter_blocked_message' => $message,
        ]);

        return $response
            ->setStatusCode(403)
            ->setBody($view);
    }
}
