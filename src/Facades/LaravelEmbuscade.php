<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Soyhuce\LaravelEmbuscade\LaravelEmbuscade
 */
class LaravelEmbuscade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Soyhuce\LaravelEmbuscade\LaravelEmbuscade::class;
    }
}
