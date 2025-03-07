<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests first', function (): void {
    $this->expectView('welcome')->first('.links a')->toContain('Docs');
});

it('fails first', function (): void {
    $this->expectView('welcome')->first('.links a')->toContain('Laracase');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Laracase` exists within '
    . '`<a href="https://laravel.com/docs">Docs</a>`.'
);
