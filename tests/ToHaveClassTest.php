<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests toHaveClass', function (): void {
    $this->expectView('button')->in('button')->toHaveClass('btn');
    $this->expectView('welcome')->in('.content')->at('div > div', 0)->toHaveClass('title');
});

it('fails toHaveClass', function (): void {
    $this->expectView('alert')->toHaveClass('missing-class');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that the class contains `missing-class` within `' . file_get_contents(__DIR__ . '/fixtures/alert.html') . '`'
);
