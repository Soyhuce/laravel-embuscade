# Test Laravel views in isolation, inspired by nunomaduro/laravel-mojito

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soyhuce/laravel-embuscade.svg?style=flat-square)](https://packagist.org/packages/soyhuce/laravel-embuscade)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-embuscade/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/soyhuce/laravel-embuscade/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-embuscade/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/soyhuce/laravel-embuscade/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![GitHub PHPStan Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-embuscade/phpstan.yml?branch=main&label=phpstan)](https://github.com/soyhuce/laravel-embuscade/actions?query=workflow%3APHPStan+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/soyhuce/laravel-embuscade.svg?style=flat-square)](https://packagist.org/packages/soyhuce/laravel-embuscade)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require soyhuce/laravel-embuscade
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-embuscade-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-embuscade-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-embuscade-views"
```

## Usage

```php
$laravelEmbuscade = new Soyhuce\LaravelEmbuscade();
echo $laravelEmbuscade->echoPhrase('Hello, Soyhuce!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bastien Philippe](https://github.com/bastien-phi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
