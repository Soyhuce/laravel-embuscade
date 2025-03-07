<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;
use Soyhuce\LaravelEmbuscade\ViewExpectation;

beforeEach(function (): void {
    ViewExpectation::macro('toHaveCharset', function (string $charset) {
        return $this->in('head')->first('meta')->toHaveAttribute('charset', $charset);
    });
});

it('has charset', function (): void {
    $this->expectView('welcome')->toHaveCharset('utf-8');
});

it('fails to have charset', function (): void {
    $this->expectView('welcome')->toHaveCharset('not-valid');
})->throws(
    AssertionFailedError::class,
    'Failed asserting that the charset `not-valid` exists within `<meta charset="utf-8">'
);
