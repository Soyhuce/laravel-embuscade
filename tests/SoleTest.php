<?php declare(strict_types=1);

use PHPUnit\Framework\AssertionFailedError;

it('tests sole', function (): void {
    $this->expectView('welcome')->sole('title')->toContain('Laravel');
});

it('fails when does not exist', function (): void {
    $this->expectView('multiple')->sole('title')->toContain('Laravel');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that 1 `title` exists within `' . file_get_contents(__DIR__ . '/fixtures/multiple.html') . '`.'
);

it('fails when multiple', function (): void {
    $this->expectView('multiple')->sole('p')->toContain('Laravel');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that 1 `p` exists within `' . file_get_contents(__DIR__ . '/fixtures/multiple.html') . '`.'
);
