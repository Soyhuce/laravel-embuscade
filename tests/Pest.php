<?php declare(strict_types=1);

use Illuminate\Support\Facades\View;
use Soyhuce\LaravelEmbuscade\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

uses()->beforeEach(function (): void {
    View::getFinder()->addNamespace('test', __DIR__ . DIRECTORY_SEPARATOR . 'fixtures');
})->in(__DIR__);
