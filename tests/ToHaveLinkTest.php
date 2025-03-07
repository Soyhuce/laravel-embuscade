<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests toHaveLink', function (): void {
    $this->expectView('button')->toHaveLink('https://link.com');
    $this->expectView('welcome')->in('.links')->first('a')->toHaveLink('https://laravel.com/docs');
    $this->expectView('welcome')->in('.links')->at('a', 6)->toHaveLink('https://vapor.laravel.com');
    $this->expectView('welcome')->in('.links')->last('a')->toHaveLink('https://github.com/laravel/laravel');
});

it('fails toHaveLink', function (): void {
    $this->expectView('button')->toHaveLink('https://link-that-is-not-there.com');
})->throws(
    AssertionFailedError::class,
    "Failed asserting that `a[href='https://link-that-is-not-there.com']` exists within `" . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);
