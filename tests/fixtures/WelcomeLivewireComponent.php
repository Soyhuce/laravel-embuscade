<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Tests\fixtures;

use Illuminate\View\View;
use Livewire\Component;

class WelcomeLivewireComponent extends Component
{
    public function render(): View
    {
        return view('test::welcome');
    }
}
