# Laravel inbox

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

If you like to store your settings in the database, you should create the migration file and execute it
```sh
php artisan vendor:publish --tag=laravel-setting-migration
php artisan migration
```

This is the config file
```php
return [

];
```

## Usage

## Security
If you find any security related issues, please open an issue or contact me directly at [justijndepover@gmail.com](justijndepover@gmail.com).

## Contribution
If you wish to make any changes or improvements to the package, feel free to make a pull request.

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.