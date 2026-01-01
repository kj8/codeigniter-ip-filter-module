<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Config;

use CodeIgniter\Config\BaseService;
use Kj8\Module\IpFilter\Providers\DbIpSetProvider;
use Kj8\Module\IpFilter\Providers\StaticIpSetProvider;
use Kj8\Module\IpFilter\Resolvers\Ci4MediaTypeResolver;
use Kj8\Module\IpFilter\Resolvers\MediaTypeResolverInterface;
use Kj8\Module\IpFilter\Resolvers\RequestTypeResolver;
use Kj8\Module\IpFilter\Responders\HTMLResponder;
use Kj8\Module\IpFilter\Responders\JSONResponder;
use Kj8\Module\IpFilter\Services\IpChecker;
use Kj8\Module\IpFilter\Services\IpFilterService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    public static function kj8IpFilterCi4MediaTypeResolver(bool $getShared = true): MediaTypeResolverInterface
    {
        if ($getShared) {
            /** @var MediaTypeResolverInterface $instance */
            $instance = static::getSharedInstance('kj8IpFilterCi4MediaTypeResolver');

            return $instance;
        }

        $supportedMediaTypes = [
            'application/json',
            'text/html',
        ];

        return new Ci4MediaTypeResolver($supportedMediaTypes);
    }

    public static function kj8IpFilterRequestTypeResolver(bool $getShared = true): RequestTypeResolver
    {
        if ($getShared) {
            /** @var RequestTypeResolver $instance */
            $instance = static::getSharedInstance('kj8IpFilterRequestTypeResolver');

            return $instance;
        }

        $responders = [
            'application/json' => JSONResponder::class,
            'text/html' => HTMLResponder::class,
        ];

        return new RequestTypeResolver($responders, self::kj8IpFilterCi4MediaTypeResolver());
    }

    public static function kj8IpFilterStatic(?bool $getShared = true): IpFilterService
    {
        if ($getShared) {
            /** @var IpFilterService $instance */
            $instance = static::getSharedInstance('kj8IpFilterStatic');

            return $instance;
        }

        return new IpFilterService(
            new StaticIpSetProvider(),
            new IpChecker()
        );
    }

    public static function kj8IpFilterDb(?bool $getShared = true): IpFilterService
    {
        if ($getShared) {
            /** @var IpFilterService $instance */
            $instance = static::getSharedInstance('kj8IpFilterDb');

            return $instance;
        }

        return new IpFilterService(
            new DbIpSetProvider(),
            new IpChecker()
        );
    }
}
