<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade;

use Illuminate\Testing\TestComponent;
use Illuminate\Testing\TestResponse;
use Illuminate\Testing\TestView;
use Livewire\Features\SupportTesting\Testable;
use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EmbuscadeServiceProvider extends PackageServiceProvider
{
    #[Override]
    public function configurePackage(Package $package): void
    {
        $package->name('laravel-embuscade');
    }

    #[Override]
    public function packageRegistered(): void
    {
        TestResponse::macro('expectView', function (): ViewExpectation {
            if ($this->responseHasView()) {
                return new ViewExpectation($this->original->render());
            }

            return new ViewExpectation($this->original);
        });

        TestView::macro('expectView', function (): ViewExpectation {
            return new ViewExpectation($this->rendered);
        });

        TestComponent::macro('expectView', function (): ViewExpectation {
            return new ViewExpectation($this->rendered);
        });

        if (class_exists(Testable::class)) {
            Testable::macro('expectView', function (): ViewExpectation {
                return new ViewExpectation($this->html(false));
            });
        }
    }

    #[Override]
    public function packageBooted(): void
    {
        // Having Blade directive would be the way to go but it looks like it is not compatible with Livewire or Flux
        // Using a precompiler does not work either because Livewire or Flux precompiler can be registered before this one.
        // We have to use BladeCompiler::prepareStringsForCompilationUsing. Not ideal but it works.

        if ($this->app->runningUnitTests() || $this->app->hasDebugModeEnabled()) {
            // Blade::directive('embuscade', function (string $expression) {
            //    return Embuscade::$selectorHtmlAttribute.
            //        "=\"".
            /*        "<?php echo {$expression}; ?>" */
            //        ."\"";
            // });
            app('blade.compiler')->prepareStringsForCompilationUsing(
                function (string $input) {
                    return preg_replace(
                        '/@embuscade\\(\'([^)]+)\'\\)/x',
                        Embuscade::$selectorHtmlAttribute . "=\"\$1\"",
                        $input
                    );
                }
            );
        } else {
            // Blade::directive('embuscade', fn (string $expression) => "");
            app('blade.compiler')->prepareStringsForCompilationUsing(
                fn (string $input) => preg_replace(
                    '/@embuscade\\(\'([^)]+)\'\\)/x',
                    '',
                    $input
                )
            );
        }


    }
}
