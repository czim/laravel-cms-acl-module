# CMS for Laravel - ACL module

Simple ACL module for the CMS.


## Installation

Add the module class to your `cms-modules.php` configuration file:

``` php
    'modules' => [
        // ...
        \Czim\CmsAclModule\AclModule::class,
    ],
```

Add the service provider to your `cms-core.php` configuration file:

``` php
    'providers' => [
        // ...
        Czim\CmsAclModule\Providers\CmsAclModuleServiceProvider::class,
        // ...
    ],
```

## API Documentation

The documentation for the ACL module API endpoints: 
https://czim.github.io/laravel-cms-acl-module

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-contributors]: ../../contributors
