<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Responders;

use CodeIgniter\HTTP\ResponseInterface;

class JSONResponder implements ResponderInterface
{
    public function respond(): ResponseInterface
    {
        /**
         * @var ResponseInterface $response
         */
        // @phpstan-ignore function.notFound
        $response = service('response');

        // @phpstan-ignore function.notFound
        $message = lang('IpFilter.blockedMessage');

        return $response
            ->setStatusCode(403)
            ->setContentType('application/json')
            ->setJSON([
                'status' => 403,
                'error' => 403,
                'messages' => ['error' => $message],
            ]);
    }
}
