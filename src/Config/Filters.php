<?php

declare(strict_types=1);

namespace Kj8\Module\IpFilter\Config;

use CodeIgniter\Config\Filters as BaseFilters;
use Kj8\Module\IpFilter\Filters\IpFilter;

class Filters extends BaseFilters
{
    public array $aliases = [
        IpFilter::NAME => IpFilter::class,
    ];

    public array $globals = [
        'before' => [],
        'after' => [],
    ];

    public array $methods = [];

    public array $filters = [];
}
