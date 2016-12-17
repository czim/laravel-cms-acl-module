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

To publish the config:

``` bash
php artisan vendor:publish
```


## Configuration

Available permissions are read from the [core](https://github.com/czim/laravel-cms-core)'s module defined ACL presences.
 
Any custom permission keys may be added to the `cms-acl-module.php` configuration file, under `permissions`:

```php
<?php
    'permissions' => [
        'example.custom.permission',
        'and.another',
    ],
```

Without further configuration, these will be presented under the header of 'Miscellaneous' in the permissions list selectable for roles.

To assign custom permissions, or even permissions provided by modules to a (new) permission group, use the `groups` configuration array.

For instance:

```php
<?php
    'groups' => [
        [
            // The type must always be 'group'
            'type'        => 'group',
            // The label will be used for the multiselect optgroup
            'label'       => 'some.translation.key',
            // If translated is set to false, the label will be displayed as is,
            // otherwise, it will passed through cms_trans()
            'translated'  => true,
            // Permissions should always be a list of available permission slug strings
            'permissions' => [
                'example.custom.permission',
                'models.app-models-post.show',
            ]
        ]
    ],
```

This would assign two permissions to a new group.

Any group left without permissions will automatically be hidden, whether it is module defined or not.

For all configuration options, see the [configuration file](config/cms-acl-module.php).


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
