<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Concerns;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert;
use Soyhuce\LaravelEmbuscade\ViewExpectation;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorCount;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorExists;
use function sprintf;

trait HasViewExpectations
{
    /**
     * Asserts that the view contains the given selector.
     */
    public function toHave(string $selector, ?int $count = null): ViewExpectation
    {
        $selector = $this->format($selector);

        if ($count === null) {
            $assertion = new CrawlerSelectorExists($selector);
            $message = "Failed asserting that `{$selector}` exists within `{$this->html}`.";
        } else {
            $assertion = new CrawlerSelectorCount($count, $selector);
            $message = "Failed asserting that {$count} `{$selector}` exists within `{$this->html}`.";
        }

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = $count === null
                ? "Failed asserting that `{$selector}` does not exist within `{$this->html}`."
                : "Failed asserting that `{$selector}` count is different from {$count} within `{$this->html}`.";
        }

        Assert::assertThat($this->crawler, $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the view contains an element with the given link.
     */
    public function toHaveLink(string $link): ViewExpectation
    {
        return $this->toHave("a[href='{$link}']");
    }

    /**
     * Asserts that the view head has a meta tag with the given attributes array.
     *
     * @param array<string, string> $attributes
     */
    public function toHaveMeta(array $attributes): ViewExpectation
    {
        $this->toHave('head');

        $properties = new Collection($attributes)
            ->map(fn ($value, $key) => sprintf("%s='%s'", $key, $value))
            ->implode('][');

        return $this->toHave("meta[{$properties}]");
    }
}
