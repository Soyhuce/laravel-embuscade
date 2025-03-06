<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Concerns;

use Illuminate\Support\Str;
use Soyhuce\LaravelEmbuscade\Embuscade;

trait FormatsSelectors
{
    protected function format(string $selector): string
    {
        if (Str::startsWith($selector, '@')) {
            $selector = preg_replace('/@(\\S+)/', '[' . Embuscade::$selectorHtmlAttribute . '="$1"]', $selector);
        }

        return Str::trim($selector);
    }
}
