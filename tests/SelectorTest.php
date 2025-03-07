<?php declare(strict_types=1);

use Soyhuce\LaravelEmbuscade\Embuscade;

it('selects from selector', function (): void {
    $this->artisan('view:clear');

    $this->view('test::selector')
        ->expectView()
        ->sole('@my-button')
        ->toContain('Click me');
});

it('customize the attribute from selector', function (): void {
    $this->artisan('view:clear');

    Embuscade::selectorHtmlAttribute('data-test');

    $this->view('test::selector')
        ->expectView()
        ->sole('[data-test="my-button"]')
        ->toContain('Click me');

    $this->view('test::selector')
        ->expectView()
        ->sole('@my-button')
        ->toContain('Click me');
});
