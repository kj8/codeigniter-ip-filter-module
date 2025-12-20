<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Filters;

use CodeIgniter\Config\Factories;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Language\Language;
use CodeIgniter\View\View;
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

        /**
         * @var View $view
         */
        $view = Factories::get('view', View::class);

        /**
         * @var ResponseInterface $response
         */
        $response = Factories::get('response', ResponseInterface::class);

        /**
         * @var Language $language
         */
        $language = Factories::get('language', Language::class);

        $ip = $request->getIPAddress();

        if (IpFilterConfig::MODE_ALLOW === $config->mode && !\in_array($ip, $config->allowedIPs, true)) {
            return $response
                ->setStatusCode(403)
                ->setBody($view->render('Kj8\Module\IpFilter\Views\blocked', [
                    'ip_filter_blocked_title' => $language->getLine('IpFilter.blockedTitle'),
                    'ip_filter_blocked_message' => $language->getLine('IpFilter.blockedMessage'),
                ]));
        }

        if (IpFilterConfig::MODE_DENY === $config->mode && \in_array($ip, $config->allowedIPs, true)) {
            return $response
                ->setStatusCode(403)
                ->setBody($view->render('Kj8\Module\IpFilter\Views\blocked'));
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
