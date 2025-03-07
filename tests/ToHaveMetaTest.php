<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests toHaveMeta', function (): void {
    $this->expectView('welcome')->toHaveMeta([
        'name' => 'viewport',
        'content' => 'width=device-width, initial-scale=1',
    ]);

    $this->expectView('welcome')->toHaveMeta([
        'charset' => 'utf-8',
    ]);
});

it('fails toHaveMeta', function (): void {
    $this->expectView('welcome')->toHaveMeta([
        'property' => 'og:title',
    ]);
})->throws(
    AssertionFailedError::class,
    "Failed asserting that `meta[property='og:title']` exists within `" . file_get_contents(__DIR__ . '/fixtures/welcome.html') . '`'
);
