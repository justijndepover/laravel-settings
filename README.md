# Laravel Settings

[![Latest Version on Packagist](https://img.shields.io/packagist/v/justijndepover/laravel-settings.svg?style=flat-square)](https://packagist.org/packages/justijndepover/laravel-settings)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/justijndepover/laravel-settings.svg?style=flat-square)](https://packagist.org/packages/justijndepover/laravel-settings)

Store settings in your Laravel application.

## Caution

This application is still in development and could implement breaking changes. Please use at your own risk.

## Installation

You can install the package with composer

```sh
composer require justijndepover/laravel-settings
```

After installation you can optionally publish your configuration file

```sh
php artisan vendor:publish --tag="laravel-settings-config"
```

## configuration

This is the config file

```php
return [

    /**
     * This setting determines what driver you want to use
     * You can overwrite this driver with your own custom one.
     */
    'driver' => \Justijndepover\Settings\Drivers\Database::class,

    /**
     * Automatically store the app locale
     * If this settings is enabled, the app locale will always be stored in database
     * omitting the need to scope your result set:
     *
     * settings()->forLocale(app()->getLocale())->get('name') becomes: settings()->get('name')
     */
    'auto_store_locale' => false,

];
```

If you chose database as your driver, you should create the migration file and execute it

```sh
php artisan vendor:publish --tag="laravel-settings-migration"
php artisan migration
```

## Usage

You have three different ways of interacting with the settings.

### Use the global helper function

```php
settings()->get('site_name')
```

### Inject the settings class with dependency injection

```php
use Justijndepover\Settings\Settings;

class HomeController extends Controller
{
    public function __invoke(Settings $settings)
    {
        $settings->get('site_name');
    }
}
```

### Use the global facade

```php
Settings::get('site_name');
```

all functionality is available with each method.

## Examples

```php
// get some value
settings('site_name');

// Is the equivalent of
settings()->get('site_name');

// return a default if no value exists
settings()->get('site_name', 'default');

// store a single value
settings()->set('site_name', 'my-personal-blog');

// store multiple values at once
settings()->set([
    'site_name' => 'my-personal-blog',
    'site_domain' => 'my-personal-blog.com',
]);

// check if a setting exists
settings()->has('site_name');

// delete all settings (both work)
settings()->flush();
settings()->delete();

// delete a single setting (both work)
settings()->forget('site_name');
settings()->delete('site_name');
```

## User settings
In addition to default settings, you can also use this package to store user settings.
Add the `HasSettings` trait to your User model

```php

use Justijndepover\Settings\Concerns\HasSettings; // add this line

class User
{
    use HasSettings; // add this line
}
```

After installing the trait, you get a `settings` method on your user
```php
// access user settings throught the model
$user = User::find(1);
$user->settings()->get('preferred_language');

// access user settings through the settings class
settings()->forUser(1)->get('preferred_language');

// all other methods are available as well
```

## Language specific settings
It's possible to store / access settings for specific locales.
```php
settings()->forLocale('en')->set('website', 'my-personal-blog.com/en');
```

## Note on how this works
The default driver always stores a `locale` and `user_id` field in the database (defaults to `null`).
Fetching data from the database will also query on these parameters.

To query something different than `null`, you should chain the `forUser` or `forLocale` between the `setting` and final method.

```php
settings()->forUser(1)->get('name');
settings()->forLocale('nl')->get('name');
settings()->forUser(1)->forLocale('nl')->get('name');
```

## Security

If you find any security related issues, please open an issue or contact me directly at [justijndepover@gmail.com](justijndepover@gmail.com).

## Contribution

If you wish to make any changes or improvements to the package, feel free to make a pull request.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
