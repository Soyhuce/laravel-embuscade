<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use PHPUnit\Framework\AssertionFailedError;

it('tests empty', function (): void {
    $this->expectView('empty')->in('.empty-div')->toBeEmpty();
    $this->expectView('empty')->in('.empty-with-empty-nodes')->toBeEmpty();
    $this->expectView('empty')->in('.empty-with-space')->toBeEmpty();
});

it('fails to be empty', function (): void {
    $this->expectView('empty')->in('.not-empty-text')->toBeEmpty();
})->throws(
    AssertionFailedError::class,
    'Failed asserting that the text `Hello` is empty.'
);
