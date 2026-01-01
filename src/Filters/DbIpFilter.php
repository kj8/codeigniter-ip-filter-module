<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Kj8\Module\IpFilter\Config\Services;

class DbIpFilter implements FilterInterface
{
    public const NAME = 'kj8_db_ipfilter';

    /**
     * @return RequestInterface|ResponseInterface|string|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        return Services::kj8IpFilterDb()->handle($request, $arguments);
    }

    /**
     * @return ResponseInterface|null
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
