<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Soyhuce\LaravelEmbuscade\Tests\fixtures\WelcomeLaravelComponent;
use Soyhuce\LaravelEmbuscade\Tests\fixtures\WelcomeLivewireComponent;
use Soyhuce\LaravelEmbuscade\ViewExpectation;

beforeEach(function (): void {
    $this->expectedHtml = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'welcome.html');
});

it('is created from TestResponse', function (): void {
    Route::get('test', function () {
        return $this->expectedHtml;
    });

    $expected = $this->get('test')
        ->assertOk()
        ->expectView();

    expect($expected)->toBeInstanceOf(ViewExpectation::class);
    expect($expected->html)->toBe($this->expectedHtml);
});

it('is created from view TestResponse', function (): void {
    Route::get('test', function () {
        return view('test::welcome');
    });

    $expected = $this->get('test')
        ->assertOk()
        ->expectView();

    expect($expected)->toBeInstanceOf(ViewExpectation::class);
    expect($expected->html)->toBe($this->expectedHtml);
});

it('is created from TestView', function (): void {
    $expected = $this->view('test::welcome')->expectView();

    expect($expected)->toBeInstanceOf(ViewExpectation::class);
    expect($expected->html)->toBe($this->expectedHtml);
});

it('is created from TestComponent', function (): void {
    $expected = $this->component(WelcomeLaravelComponent::class)->expectView();

    expect($expected)->toBeInstanceOf(ViewExpectation::class);
    expect($expected->html)->toBe($this->expectedHtml);
});

it('is created from livewire test component', function (): void {
    $expected = Livewire::test(WelcomeLivewireComponent::class)->expectView();

    expect($expected)->toBeInstanceOf(ViewExpectation::class);
    $expected->first('head title')->toHaveText('Laravel');
});
