<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade;

class Embuscade
{
    public static string $selectorHtmlAttribute = 'data-embuscade';

    public static function selectorHtmlAttribute(string $selectorHtmlAttribute): void
    {
        self::$selectorHtmlAttribute = $selectorHtmlAttribute;
    }
}
