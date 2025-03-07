<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests toHaveText', function (): void {
    $this->expectView('button')->toHaveText('Click me');
    $this->expectView('welcome')->in('head title')->toHaveText('Laravel');
});

it('fails toHaveText', function (): void {
    $this->expectView('button')->toHaveText('Do not click me');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Do not click me` is text of `' . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);

it('tests not toHaveText', function (): void {
    $this->expectView('button')->not->toHaveText('Do not click me');
});

it('fails not toHaveText', function (): void {
    $this->expectView('button')->not->toHaveText('Click me');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Click me` is not text of `' . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);
