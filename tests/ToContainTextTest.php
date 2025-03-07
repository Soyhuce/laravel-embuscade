<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests toContainText', function (): void {
    $this->expectView('button')->toContainText('Click');
    $this->expectView('welcome')->in('head title')->toContainText('Laravel');
});

it('fails toContainText', function (): void {
    $this->expectView('button')->toContainText('Do not');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Do not` is contained in text of `' . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);

it('tests not toContainText', function (): void {
    $this->expectView('button')->not->toContainText('Do not');
});

it('fails not toContainText', function (): void {
    $this->expectView('button')->not->toContainText('Click');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Click` is not contained in text of `' . file_get_contents(__DIR__ . '/fixtures/button.html') . '`'
);
