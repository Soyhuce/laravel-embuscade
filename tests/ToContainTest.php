<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests toContain', function (): void {
    $this->expectView('button')->toContain('Click me');
    $this->expectView('welcome')->toContain('<title>Laravel</title>');
});

it('fails toContain', function (): void {
    $this->expectView('button')->toContain('Do not click me');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Do not click me` exists within `' . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);

it('tests not toContain', function (): void {
    $this->expectView('button')->not->toContain('Do not click me');
});

it('fails not toContain', function (): void {
    $this->expectView('button')->not->toContain('Click me');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Click me` does not exist within `' . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);

it('tests toContain in multiple root nodes', function (): void {
    $this->expectView('multiple')
        ->toContain('FIRST_PARAGRAPH')
        ->toContain('SECOND_PARAGRAPH');
});

it('tests toContain in malformed html', function (): void {
    $this->expectView('malformed')
        ->toContain('BEFORE')
        ->toContain('AFTER')
        ->toContain('WIDGETBEF')
        ->toContain('WIDGETAFT');
});
