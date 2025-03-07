<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests at', function (): void {
    $this->expectView('welcome')->at('.links a', 1)->toContain('Laracasts');
});

it('fails at', function (): void {
    $this->expectView('welcome')->at('.links a', 1)->toContain('Laracase');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `Laracase` exists within '
    . '`<a href="https://laracasts.com">Laracasts</a>`.'
);
