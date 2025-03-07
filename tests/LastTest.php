<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests last', function (): void {
    $this->expectView('welcome')->last('.links a')->toContain('GitHub');
});

it('fails last', function (): void {
    $this->expectView('welcome')->last('.links a')->toContain('Laracase');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Laracase` exists within '
    . '`<a href="https://github.com/laravel/laravel">GitHub</a>`.'
);
