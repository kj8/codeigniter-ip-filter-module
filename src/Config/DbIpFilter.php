<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Config;

use CodeIgniter\Config\BaseConfig;

class DbIpFilter extends BaseConfig
{
    /**
     * Tryb działania: 'allow' lub 'deny'
     * - allow → przepuszcza tylko adresy z listy
     * - deny  → blokuje adresy z listy.
     */
    public const MODE_ALLOW = 'allow';
    public const MODE_DENY = 'deny';

    /**
     * Nazwy tabel w bazie.
     */
    public string $setsTable = 'ip_filter_sets';
    public string $ipsTable = 'ip_filter_ips';

    /**
     * Domyślny czas przechowywania w cache (sekundy).
     */
    public int $cacheTTL = 300;
}
