<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelEmbuscadeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-embuscade');
    }
}
