<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Controllers;

use CodeIgniter\Config\Services;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class TestController extends Controller
{
    public function index(): ResponseInterface
    {
        /**
         * @var ResponseInterface $response
         */
        $response = Services::response();

        return $response
            ->setStatusCode(200)
            ->setBody('ok');
    }
}
