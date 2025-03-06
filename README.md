# Test Laravel views in isolation, inspired by nunomaduro/laravel-mojito

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soyhuce/laravel-embuscade.svg?style=flat-square)](https://packagist.org/packages/soyhuce/laravel-embuscade)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-embuscade/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/soyhuce/laravel-embuscade/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-embuscade/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/soyhuce/laravel-embuscade/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![GitHub PHPStan Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-embuscade/phpstan.yml?branch=main&label=phpstan)](https://github.com/soyhuce/laravel-embuscade/actions?query=workflow%3APHPStan+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/soyhuce/laravel-embuscade.svg?style=flat-square)](https://packagist.org/packages/soyhuce/laravel-embuscade)

Test your Laravel views in isolation, interacting directly with the HTML.

```php
$this->get('/')->expectView()->toContain('Laravel');

$this->view('menu', ['links' => $links])
    ->expectView()
    ->in('.links')
    ->first('a')
    ->toHave('href', 'https://laravel.com/docs')
    ->toHaveClass('btn')
    ->toHaveText('Documentation');
```

## Installation

You can install the package via composer:

```bash
composer require --dev soyhuce/laravel-embuscade
```

## Usage

### Accessing view expectations

The most basic way to access the ViewExpectation is to create it with an HTML string :

```php
use Soyhuce\LaravelEmbuscade\ViewExpectation;

new ViewExpectation($html);
```

As this is not the most convenient way, you can create the View expectation from various objects: 
```php
// From a TestResponse 
$viewExpectation = $this->get('/')->expectView(); 

// From a TestView
$viewExpectation = $this->view('home', ['links' => $links])->expectView();

// From a TestComponent
$viewExpectation = $this->component(Home::class, ['links' => $links])->expectView();
```

If you use Livewire, you can also create a ViewExpectation from a Livewire test component:
```php
$viewExpectation = Livewire::test(HomePage::class, ['links' => $links])->expectView();
```

### Navigating the view

Once the ViewExpectation is created, you can navigate the view using the following methods:
```php
 // Selects all elements matching the CSS selector
$viewExpectation->in($cssSeclector);
 // Selects the nth element matching the CSS selector, index starts at 0 !
$viewExpectation->at($cssSelector, $index);
 // Selects the first element matching the CSS selector
$viewExpectation->first($cssSelector);
 // Selects the last element matching the CSS selector
$viewExpectation->last($cssSeclector);
 // Selects the only element matching the CSS selector
$viewExpectation->sole($cssSelector);
```

`$cssSelector` must be any valid CSS selector, like `.class`, `#id`, `tag`, `tag.class`, `tag#id`, `tag[attr=value]`, etc.

> Note : Some pseudo-classes and pseudo-elements are not supported, like `:hover`, `:before`, `:after`, `:has`, etc.

#### Embuscade selectors

You can also use Embuscade selectors to navigate the view as navigating CSS selectors can be cumbersome:
```html
<button data-embuscade="login-button">Login</button>
```

```php
$viewExpectation->sole('@login-button')
    ->...
```

You can also use the `@embuscade` directive to generate Embuscade selectors in your blade views:
```html
<button @embuscade('login-button')>Login</button>
```

The `data-embuscade` attribute will be added to the element, only on testing environment or is debug mode is enabled.

> Note : Because @embuscade is not really a blade directive, it requires use of single quotes `'` to work and won't have access to execution context.
> `@embuscade("login-button")` will not work.
> ```php
> @foreach ($array as $key => $value)
>     <div @embuscade('login-button-{{ $key }}')>{{ $value}}</div>
> @endforeach
> ``` 
> will not work either.

You can customize the HTML attribute Embuscade will use for the selectors using `selectorHtmlAttribute` method:

```php
use Soyhuce\LaravelEmbuscade\Embuscade;

Embuscade::selectorHtmlAttribute('data-test'); // or 'dusk' if you use Dusk and want to leverage existing Dusk selectors.
```

### Expectations

#### Expectations on entire view

Some expectations will be applied to the entire view:
```php
// Expects the view to contain at least an element matching the CSS selector
$viewExpectation->toHave('.links a');
// Expects the view to contain exactly n elements matching the CSS selector
$viewExpectation->toHave('.links a', 2);
// Expects the view to contain at least one a element pointing to $link
$viewExpectation->toHaveLink('https://laravel.com/docs');
// Expect the view contains a meta tag with the given attributes in head section
$viewExpectation->toHaveMeta(['property' => 'og:title', 'content' => 'Laravel']);
// Expect the view text equals given text
$viewExpectation->toHaveText('Laravel');
// Expect the view text contains given text
$viewExpectation->toContainText('Documentation');
// Expect the view text is empty
$viewExpectation->toBeEmpty();
// Expect the view html contains given content
$viewExpectation->toContain('<a href="https://laravel.com/docs">Documentation</a>');
```

#### Expectations on current element

Other expectation will only look at current root:
```php
// Expect the element to have the given attribute
$viewExpectation->toHaveAttribute('disabled');
// Expect the element to have the given attribute with the given value
$viewExpectation->toHaveAttribute('href', 'https://laravel.com/docs');
// Expect the element to have the given attribute containing the given value
$viewExpectation->toHaveAttributeContaining('class', 'btn');
// Expect the element to have the given class
$viewExpectation->toHaveClass('btn');
// Expect the element to be disabled
$viewExpectation->toBeDisabled();
```

#### Negating expectation

You can negate any expectation by calling `not` before the expectation:
```php
$viewExpectation->not->toHave('.links a');
$viewExpectation->not->toBeDisabled();
```

The negation will only apply to the next expectation.
```php 
$this->view('menu', ['links' => $links])
    ->expectView()
    ->in('.links')
    ->first('a')
    ->toHave('href', 'https://laravel.com/docs')
    ->not->toHaveAttribute('target')
    ->toHaveClass('btn')
    ->toHaveText('Documentation');
```

### Navigating and expectations on elements

You can navigate and apply expectations on elements in a single chain, in order to not loose focus on the current element:
Given the following HTML:
```html
<fieldset disabled>
  <legend>Disabled fieldset</legend>
  <p>
    <label>
      Name: <input type="radio" name="radio" value="regular" /> Regular
    </label>
  </p>
  <p>
    <label>Number: <input type="number" /></label>
  </p>
</fieldset>
```

you can test it with the following code:

```php
use Soyhuce\LaravelEmbuscade\ViewExpectation;

$this->view('test')
    ->expectView()
    ->toBeDisabled()
    ->sole('legend', fn(ViewExpectation $expect) => $expect->toHaveText('Disabled fieldset'))
    ->at('p input', 0, fn(ViewExpectation $expect) => $expect->toHaveAttribute('type', 'radio'))
    ->at('p input', 1, fn(ViewExpectation $expect) => $expect->toHaveAttribute('type', 'number'));
```

Every selection method will allow you to pass a closure that will receive a new ViewExpectation, focused on the selected element.

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
- [Nuno Maduro](https://github.com/nonumaduro) for the inspiration with [laravel-mojito](https://github.com/nunomaduro/laravel-mojito)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
