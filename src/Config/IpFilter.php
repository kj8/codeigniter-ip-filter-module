<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Config;

use CodeIgniter\Config\BaseConfig;

class IpFilter extends BaseConfig
{
    /**
     * Tryb działania: 'allow' lub 'deny'
     * - allow → przepuszcza tylko adresy z listy
     * - deny  → blokuje adresy z listy.
     */
    public const MODE_ALLOW = 'allow';
    public const MODE_DENY = 'deny';

    public string $mode = self::MODE_ALLOW;

    /**
     * Zestawy IP.
     *
     * @var array<string, array{
     *     mode: self::MODE_ALLOW|self::MODE_DENY,
     *     ips: list<string>
     * }>
     */
    public array $sets = [
        'default' => [
            'mode' => self::MODE_ALLOW,
            'ips' => [
                '127.0.0.1',
                '::1',
            ],
        ],
        'admin' => [
            'mode' => self::MODE_ALLOW,
            'ips' => [
                '127.0.0.1',
                '::1',
            ],
        ],
        'api' => [
            'mode' => self::MODE_ALLOW,
            'ips' => [
                '10.0.0.1',
                '10.0.0.2',
            ],
        ],
    ];
}
