<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Services;

use Kj8\Module\IpFilter\Config\IpFilter as IpFilterConfig;

class IpChecker
{
    /**
     * @param array<string, true> $ipMap
     */
    public function isBlocked(string $mode, string $ip, array $ipMap): bool
    {
        $exists = isset($ipMap[$ip]);

        return (IpFilterConfig::MODE_ALLOW === $mode && !$exists)
            || (IpFilterConfig::MODE_DENY === $mode && $exists);
    }
}
