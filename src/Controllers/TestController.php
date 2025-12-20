<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Controllers;

use CodeIgniter\Config\Factories;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class TestController extends Controller
{
    public function index(): ResponseInterface
    {
        /**
         * @var ResponseInterface $response
         */
        $response = Factories::get('response', ResponseInterface::class);

        return $response
            ->setStatusCode(200)
            ->setBody('ok');
    }
}
