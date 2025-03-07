<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests;

use Illuminate\Encryption\Encrypter;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Soyhuce\LaravelEmbuscade\EmbuscadeServiceProvider;
use Soyhuce\LaravelEmbuscade\ViewExpectation;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            EmbuscadeServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('app.key', 'base64:' . base64_encode(Encrypter::generateKey($app['config']['app.cipher'])));
    }

    public function expectView(string $name): ViewExpectation
    {
        return new ViewExpectation((string) file_get_contents(
            __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $name . '.html'
        ));
    }
}
