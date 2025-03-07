<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Concerns;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\StringContains;
use Soyhuce\LaravelEmbuscade\ViewExpect;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorCount;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorExists;
use function sprintf;

trait HasViewExpectations
{
    /**
     * Asserts that the view contains the given selector.
     */
    public function toHave(string $selector, ?int $count = null): ViewExpect
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
    public function toHaveLink(string $link): ViewExpect
    {
        return $this->toHave("a[href='{$link}']");
    }

    /**
     * Asserts that the view head has a meta tag with the given attributes array.
     *
     * @param array<string, string> $attributes
     */
    public function toHaveMeta(array $attributes): ViewExpect
    {
        $this->toHave('head');

        $properties = new Collection($attributes)
            ->map(fn ($value, $key) => sprintf("%s='%s'", $key, $value))
            ->implode('][');

        return $this->toHave("meta[{$properties}]");
    }

    /**
     * Asserts that the current element text is the expected one.
     */
    public function toHaveText(string $text): ViewExpect
    {
        $assertion = new IsIdentical($text);
        $message = "Failed asserting that `{$text}` is text of `{$this->html}`.";

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = "Failed asserting that `{$text}` is not text of `{$this->html}`.";
        }

        Assert::assertThat($this->crawler->text(null, true), $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the current element contains the expected one.
     */
    public function toContainText(string $text): ViewExpect
    {
        $assertion = new StringContains($text);
        $message = "Failed asserting that `{$text}` is contained in text of `{$this->html}`.";

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = "Failed asserting that `{$text}` is not contained in text of `{$this->html}`.";
        }

        Assert::assertThat($this->crawler->text(null, true), $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the current element has no text content.
     */
    public function toBeEmpty(): ViewExpect
    {
        $content = $this->crawler->text(null, true);

        $assertion = new IsEmpty();
        $message = "Failed asserting that the text `{$content}` is empty.";

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = "Failed asserting that the text `{$content}` is not empty.";
        }

        Assert::assertThat($content, $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the current element contains the given content.
     */
    public function toContain(string $content): ViewExpect
    {
        $assertion = new StringContains($content);
        $message = "Failed asserting that `{$content}` exists within `{$this->html}`.";

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = "Failed asserting that `{$content}` does not exist within `{$this->html}`.";
        }

        Assert::assertThat($this->html, $assertion, $message);

        return $this;
    }
}
