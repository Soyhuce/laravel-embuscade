<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests\fixtures;

use Illuminate\View\Component;
use Illuminate\View\View;

class WelcomeLaravelComponent extends Component
{
    public function render(): View
    {
        return view('test::welcome');
    }
}
