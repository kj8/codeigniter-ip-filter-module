<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Responders;

use CodeIgniter\HTTP\ResponseInterface;

class HTMLResponder implements ResponderInterface
{
    public function respond(): ResponseInterface
    {
        /**
         * @var ResponseInterface $response
         */
        // @phpstan-ignore function.notFound
        $response = service('response');

        // @phpstan-ignore function.notFound
        $title = lang('IpFilter.blockedTitle');

        // @phpstan-ignore function.notFound
        $message = lang('IpFilter.blockedMessage');

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
