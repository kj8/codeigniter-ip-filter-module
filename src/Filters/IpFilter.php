<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Kj8\Module\IpFilter\Config\Services;

class IpFilter implements FilterInterface
{
    public const NAME = 'kj8_ipfilter';

    /**
     * @return RequestInterface|ResponseInterface|string|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        return Services::kj8IpFilterStatic()->handle($request, $arguments);
    }

    /**
     * @return ResponseInterface|null
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
