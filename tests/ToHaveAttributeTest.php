<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests toHaveAttribute', function (): void {
    $this->expectView('button')->toHaveAttribute('prop', 'value');
    $this->expectView('welcome')->toHaveAttribute('lang', 'en');
    $this->expectView('welcome')->in('head')->first('meta')->toHaveAttribute('charset', 'utf-8');
});

it('tests toHaveAttribute without value', function (): void {
    $this->expectView('button')->toHaveAttribute('prop');
});

it('fails toHaveAttribute', function (): void {
    $this->expectView('button')->toHaveAttribute('prop', 'missing-value');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that the prop `missing-value` exists within `' . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);

it('fails toHaveAttribute without prop', function (): void {
    $this->expectView('button')->toHaveAttribute('missing-attribute');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that the missing-attribute exists within `' . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);
