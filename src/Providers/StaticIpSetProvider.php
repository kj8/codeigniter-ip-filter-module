<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Providers;

use CodeIgniter\Config\Factories;
use Kj8\Module\IpFilter\Config\IpFilter as IpFilterConfig;

class StaticIpSetProvider implements IpSetProviderInterface
{
    /**
     * @return array{
     *     mode: IpFilterConfig::MODE_ALLOW|IpFilterConfig::MODE_DENY,
     *     ips: array<string, true>
     * }
     */
    public function getSet(string $setName): array
    {
        /** @var IpFilterConfig $config */
        $config = Factories::config('IpFilter');

        if (!isset($config->sets[$setName])) {
            throw new \RuntimeException("IpFilter: no configuration for set '$setName'");
        }

        $set = $config->sets[$setName];

        $mode = $set['mode'];
        $ips = $set['ips'];

        $ipMap = [];
        foreach ($ips as $ipAddress) {
            $ipMap[$ipAddress] = true;
        }

        return [
            'mode' => $mode,
            'ips' => $ipMap,
        ];
    }
}
