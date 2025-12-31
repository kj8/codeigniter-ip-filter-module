<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Responders;

use CodeIgniter\HTTP\ResponseInterface;

interface ResponderInterface
{
    public function respond(): ResponseInterface;
}
