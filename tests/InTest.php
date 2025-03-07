<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests in', function (): void {
    $this->expectView('alert')->in('button')->toHaveClass('btn');
    $this->expectView('welcome')->in('title')->toContain('Laravel');
    $this->expectView('welcome')->in('.links a')->toContain('Laracast');
});

it('fails in', function (): void {
    $this->expectView('alert')->in('something-not-there')->toHaveClass('btn');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `something-not-there` exists within `' . file_get_contents(__DIR__ . '/fixtures/alert.html') . '`'
);
