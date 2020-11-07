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

After installation you should publish your configuration file
```sh
php artisan vendor:publish --tag="laravel-settings-config"
```
## configuration
This is the config file
```php
return [

    /*
    * This setting determines what driver you want to use
    * possible values are: 'database', 'json'
    */
    'driver' => 'database',

    /*
    * If you chose json as your driver, this will be the path where
    * the settings are stored
    */
    'path_on_disk' => '',

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
use Justijndepover\Settings\Setting;

class HomeController extends Controller
{
    public function __invoke(Setting $settings)
    {
        $settings->get('site_name')
    }
}
```
### Use the global facade
```php
Setting::get('site_name');
```

all functionality is available with each method.

## Examples
```php
// get some values
$settings->get('site_name');

// return a default if no value exists
$settings->get('site_name', 'default');

// store a single value
$settings->set('site_name', 'my-personal-blog');

// store multiple values at once
$settings->set([
    'site_name' => 'my-personal-blog',
    'site_domain' => 'my-personal-blog.com',
]);

// check if a setting exists
$settings->has('site_name');

// delete all settings (both work)
$settings->flush();
$settings->delete();

// delete a single setting (both work)
$settings->forget('site_name');
$settings->delete('site_name');
```

## Security
If you find any security related issues, please open an issue or contact me directly at [justijndepover@gmail.com](justijndepover@gmail.com).

## Contribution
If you wish to make any changes or improvements to the package, feel free to make a pull request.

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.