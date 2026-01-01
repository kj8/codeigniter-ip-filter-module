<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Providers;

use Kj8\Module\IpFilter\Config\IpFilter as IpFilterConfig;

interface IpSetProviderInterface
{
    /**
     * @return array{
     *     mode: IpFilterConfig::MODE_ALLOW|IpFilterConfig::MODE_DENY,
     *     ips: array<string, true>
     * }
     */
    public function getSet(string $setName): array;
}
