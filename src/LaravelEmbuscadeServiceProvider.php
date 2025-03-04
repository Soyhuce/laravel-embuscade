<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Soyhuce\LaravelEmbuscade\Commands\LaravelEmbuscadeCommand;

class LaravelEmbuscadeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-embuscade')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_embuscade_table')
            ->hasCommand(LaravelEmbuscadeCommand::class);
    }
}
