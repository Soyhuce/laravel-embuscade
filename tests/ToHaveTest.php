<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests toHave', function (): void {
    $this->expectView('alert')->toHave('button');
    $this->expectView('welcome')->toHave('head');
    $this->expectView('welcome')->in('body')->toHave('.content');
});

it('fails toHave', function (): void {
    $this->expectView('alert')->toHave('form');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `form` exists within `' . file_get_contents(__DIR__ . '/fixtures/alert.html') . '`.'
);

it('tests not toHave', function (): void {
    $this->expectView('alert')->not->toHave('form');
});

it('fails not toHave', function (): void {
    $this->expectView('alert')->not->toHave('button');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that `button` does not exist within `' . file_get_contents(__DIR__ . '/fixtures/alert.html') . '`.'
);
