<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Providers;

use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Config\Factories;
use Kj8\Module\IpFilter\Config\DbIpFilter as DbIpFilterConfig;
use Kj8\Module\IpFilter\Config\IpFilter as IpFilterConfig;

class DbIpSetProvider implements IpSetProviderInterface
{
    /**
     * @return array{
     *     mode: IpFilterConfig::MODE_ALLOW|IpFilterConfig::MODE_DENY,
     *     ips: array<string, true>
     * }
     */
    public function getSet(string $setName): array
    {
        /** @var DbIpFilterConfig $config */
        $config = Factories::config(DbIpFilterConfig::class);

        return $this->loadSetData($setName, $config);
    }

    /**
     * @return array{
     *     mode: IpFilterConfig::MODE_ALLOW|IpFilterConfig::MODE_DENY,
     *     ips: array<string, true>
     * }
     */
    private function loadSetData(string $setName, DbIpFilterConfig $config): array
    {
        /** @var CacheInterface $cache */
        // @phpstan-ignore function.notFound
        $cache = service('cache');

        // Spróbuj pobrać z cache
        $cacheKey = 'ipfilter_set_'.$setName;
        $data = $cache->get($cacheKey);

        if (null !== $data) {
            $this->assertValidCacheData($data);

            return $data;
        }

        $data = $this->loadSetDataFromDb($setName, $config);

        $cache->save($cacheKey, $data, $config->cacheTTL);

        return $data;
    }

    /**
     * @return array{
     *     mode: IpFilterConfig::MODE_ALLOW|IpFilterConfig::MODE_DENY,
     *     ips: array<string, true>
     * }
     */
    private function loadSetDataFromDb(string $setName, DbIpFilterConfig $config): array
    {
        // @phpstan-ignore class.notFound
        $db = \Config\Database::connect();

        $set = $db->table($config->setsTable)
            ->where('set_name', $setName)
            ->get()
            ->getRowArray();

        if (!$set) {
            throw new \RuntimeException("IpFilter: set '{$setName}' not found in DB");
        }

        $mode = $set['mode'];
        $setId = $set['id'];

        // Pobierz wszystkie IP w tym zestawie
        $ips = $db->table($config->ipsTable)
            ->select('ip_address')
            ->where('set_id', $setId)
            ->get()
            ->getResultArray();

        // Zrób z nich szybki hash map do O(1) sprawdzenia
        $ipMap = [];
        foreach ($ips as $row) {
            $ipMap[$row['ip_address']] = true;
        }

        return [
            'mode' => $mode,
            'ips' => $ipMap,
        ];
    }

    /**
     * @phpstan-assert array{mode: IpFilterConfig::MODE_ALLOW|IpFilterConfig::MODE_DENY, ips: array<string, true>} $data
     */
    private function assertValidCacheData(mixed $data): void
    {
        if (
            !\is_array($data)
            || !isset($data['mode'], $data['ips'])
            || !\is_string($data['mode'])
            || !\is_array($data['ips'])
        ) {
            throw new \UnexpectedValueException('Invalid cache data');
        }
    }
}
