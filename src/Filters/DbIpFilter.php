<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Filters;

use CodeIgniter\Config\Factories;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Kj8\Module\IpFilter\Config\DbIpFilter as DbIpFilterConfig;
use Kj8\Module\IpFilter\Config\Services;

class DbIpFilter implements FilterInterface
{
    public const NAME = 'kj8_db_ipfilter';

    /**
     * @return RequestInterface|ResponseInterface|string|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $setName = $arguments[0] ?? 'default';
        $ip = $request->getIPAddress();

        /** @var DbIpFilterConfig $config */
        $config = Factories::config(DbIpFilterConfig::class);

        $cache = \Config\Services::cache();

        // Spróbuj pobrać z cache
        $cacheKey = 'ipfilter_set_'.$setName;
        $data = $cache->get($cacheKey);

        if (!$data) {
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

            unset($ips);

            $data = [
                'mode' => $mode,
                'ips' => $ipMap,
            ];

            $cache->save($cacheKey, $data, $config->cacheTTL);
        }

        $mode = $data['mode'];
        $ipMap = $data['ips'];

        if (
            (DbIpFilterConfig::MODE_ALLOW === $mode && !isset($ipMap[$ip]))
            || (DbIpFilterConfig::MODE_DENY === $mode && isset($ipMap[$ip]))
        ) {
            return Services::kj8IpFilterRequestTypeResolver()
                ->resolve($request)
                ->respond();
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
