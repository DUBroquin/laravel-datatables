# Laravel-datatable package for vuetable2

This package is a fork of yajra/laravel-vuetable created by Arjay Angeles <aqangeles@gmail.com>

## Requirements
- [PHP >= 5.6.4](http://php.net/)
- [Laravel 5.4+](https://github.com/laravel/framework)

## Laravel Version Compatibility

 Laravel  | Package
:---------|:----------
 4.2.x    | 3.x
 5.0.x    | 6.x
 5.1.x    | 6.x
 5.2.x    | 6.x
 5.3.x    | 6.x
 5.4.x    | 7.x

## Laravel 5.4 Upgrade Guide
There are breaking changes since Laravel 5.4 and vuetable v7.0.
If you are upgrading from v6.x to v7.x, please see [upgrade guide](https://yajrabox.com/docs/laravel-vuetable/7.0/upgrade).

## Quick Installation
```bash
$ composer require yajra/laravel-vuetable-oracle:^7.5
```

#### Service Provider
`dubroquin\vuetable;\vuetableServiceProvider::class`

#### Facade
`vuetable` facade is automatically registered as an alias for `dubroquin\vuetable;\Facades\vuetable` class. 

#### Configuration and Assets
```bash
$ php artisan vendor:publish --tag=vuetable
```

And that's it! Start building out some awesome vuetable!

## Debugging Mode
To enable debugging mode, just set `APP_DEBUG=true` and the package will include the queries and inputs used when processing the table.

**IMPORTANT:** Please make sure that APP_DEBUG is set to false when your app is on production.

## PHP ARTISAN SERVE BUG
Please avoid using `php artisan serve` when developing with the package. 
There are known bugs when using this where Laravel randomly returns a redirect and 401 (Unauthorized) if the route requires authentication and a 404 NotFoundHttpException on valid routes.

It is advise to use [Homestead](https://laravel.com/docs/5.4/homestead) or [Valet](https://laravel.com/docs/5.4/valet) when working with the package.

## Contributing

Please see [CONTRIBUTING](https://github.com/yajra/laravel-vuetable/blob/master/.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email [aqangeles@gmail.com](mailto:aqangeles@gmail.com) instead of using the issue tracker.

## Credits

- [Arjay Angeles](https://github.com/yajra)
- [bllim/laravel4-vuetable-package](https://github.com/bllim/laravel4-vuetable-package)
- [All Contributors](https://github.com/yajra/laravel-vuetable/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/yajra/laravel-vuetable/blob/master/LICENSE.md) for more information.

## Buy me a coffee
<a href='https://pledgie.com/campaigns/29515'><img alt='Click here to lend your support to: Laravel vuetable and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/29515.png?skin_name=chrome' border='0' ></a>
