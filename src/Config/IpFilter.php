<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Config;

use CodeIgniter\Config\BaseConfig;

class IpFilter extends BaseConfig
{
    public const MODE_ALLOW = 'allow';
    public const MODE_DENY = 'deny';

    /**
     * Summary of allowedIPs.
     *
     * @var list<string>
     */
    public array $allowedIPs = [
        '127.0.0.1',
        '::1',
    ];

    /**
     * Tryb działania: 'allow' lub 'deny'
     * - allow → przepuszcza tylko adresy z listy
     * - deny  → blokuje adresy z listy.
     */
    public string $mode = self::MODE_ALLOW;
}
