# CodeIgniter 4 module to filter IP addresses for access control

## Installation

```bash
composer config repositories.kj8-codeigniter-ip-filter-module vcs https://github.com/kj8/codeigniter-ip-filter-module
composer require kj8/codeigniter4-ip-filter-module:^1.0
```

```php
// default filter
$routes->get('home', 'Home::index', ['filter' => 'kj8_ipfilter']);
$routes->get('home', 'Home::index', ['filter' => 'kj8_ipfilter:default']);

$routes->group('admin', ['filter' => 'kj8_ipfilter:admin'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
});

$routes->group('api', ['filter' => 'kj8_ipfilter:api'], function ($routes) {
    $routes->get('users', 'Api\Users::index');
});

$routes->get('reports', 'Reports::index', ['filter' => 'kj8_ipfilter:admin']);
```
