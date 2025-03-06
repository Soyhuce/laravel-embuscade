<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade;

use DOMElement;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\StringContains;
use Soyhuce\LaravelEmbuscade\Exceptions\RootElementNotFound;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorCount;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorExists;

/**
 * @property-read ViewExpectation $not
 */
class ViewExpectation
{
    use Macroable;

    public Crawler $crawler;

    protected bool $negate = false;

    public function __construct(
        public string $html,
    ) {
        $this->crawler = new Crawler($this->html);

        // If the view is not a full HTML document, the Crawler will try to fix it
        // adding an html and a body tag, so we need to crawl back down
        // to the relevant portion of HTML
        if (!str_contains($html, '</html>')) {
            $this->crawler = $this->crawler->children()->children();
        }
    }

    /**
     * Creates a new view assertion with the given selector.
     *
     * @param callable(ViewExpectation): void|null $callback
     */
    public function in(string $selector, ?callable $callback = null ): self
    {
        if($callback !== null) {
            $callback($this->in($selector));

            return $this;
        }

        $selector = $this->format($selector);

        $this->toHave($selector);

        $filteredHtml = $this->crawler
            ->children()
            ->filter($selector)
            ->each(fn (Crawler $node): string => $node->outerHtml());

        return new self(new Collection($filteredHtml)->implode(''));
    }

    /**
     * Creates a new view assertion with the given selector at the given position.
     *
     * @param callable(ViewExpectation): void|null $callback
     */
    public function at(string $selector, int $position, ?callable $callback = null): self
    {
        if($callback !== null) {
            $callback($this->at($selector, $position));

            return $this;
        }

        $selector = $this->format($selector);

        $node = $this->crawler->filter($selector)->eq($position);

        return new self($node->outerHtml());
    }

    /**
     * Creates a new view assertion with the given selector at the first position.
     *
     * @param callable(ViewExpectation): void|null $callback
     */
    public function first(string $selector, ?callable $callback = null): self
    {
        if($callback !== null) {
            $callback($this->first($selector));

            return $this;
        }

        $selector = $this->format($selector);

        $node = $this->crawler->filter($selector)->first();

        return new self($node->outerHtml());
    }

    /**
     * Creates a new view assertion with the given selector at the last position.
     *
     * @param callable(ViewExpectation): void|null $callback
     */
    public function last(string $selector,?callable $callback = null): self
    {
        if($callback !== null) {
            $callback($this->last($selector));

            return $this;
        }

        $selector = $this->format($selector);

        $node = $this->crawler->filter($selector)->last();

        return new self($node->outerHtml());
    }

    /**
     * Creates a new view assertion with the given unique selector.
     *
     * @param callable(ViewExpectation): void|null $callback
     */
    public function sole(string $selector,?callable $callback = null): self
    {
        if($callback !== null) {
            $callback($this->sole($selector));

            return $this;
        }

        $selector = $this->format($selector);

        $this->toHave($selector, 1);

        $node = $this->crawler->filter($selector)->first();

        return new self($node->outerHtml());
    }

    /**
     * Asserts that the view contains the given text.
     */
    public function toContain(string $text): self
    {
        $assertion = new StringContains($text);
        $message = "Failed asserting that the text `{$text}` exists within `{$this->html}`.";

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = "Failed asserting that the text `{$text}` does not exists within `{$this->html}`.";
        }

        Assert::assertThat($this->html, $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the view, at given selector, has no content.
     */
    public function toBeEmpty(): self
    {
        $content = '';
        foreach ($this->crawler->getIterator() as $node) {
            $content .= mb_trim($node->textContent);
        }

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
     * Asserts that the view, at the **root element**, contains the given attribute value.
     */
    public function toHaveAttribute(string $attribute, string $value): self
    {
        $assertion = new IsIdentical($value);
        $message = "Failed asserting that the {$attribute} `{$value}` exists within `{$this->html}`.";

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = "Failed asserting that the {$attribute} `{$value}` does not exist within `{$this->html}`.";
        }

        Assert::assertThat($this->getRootElement()->getAttribute($attribute), $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the view, at the **root element**, contains the given attribute value.
     */
    public function toHaveAttributeContaining(string $attribute, string $value): self
    {
        $assertion = new StringContains($value);
        $message = "Failed asserting that the {$attribute} contains `{$value}` within `{$this->html}`.";

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = "Failed asserting that the {$attribute} does not contain `{$value}` within `{$this->html}`.";
        }

        Assert::assertThat($this->getRootElement()->getAttribute($attribute), $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the view, at the **root element**, contains an element with the given class.
     */
    public function toHaveClass(string $class): self
    {
        return $this->toHaveAttributeContaining('class', $class);
    }

    /**
     * Asserts that the view, at the **root element**, contains an element with disabled attribute.
     */
    public function toBeDisabled(): self
    {
        return $this->toHaveAttribute('disabled', 'disabled');
    }

    /**
     * Asserts that the view contains an element with the given link.
     */
    public function toHaveLink(string $link): self
    {
        return $this->toHave("a[href='{$link}']");
    }

    /**
     * Asserts that the view contains the given selector.
     */
    public function toHave(string $selector, ?int $count = null): self
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
     * Asserts that the view text, at the **root element**, is the expected one.
     */
    public function textToBe(string $text): self
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
     * Asserts that the view text, at the **root element**, contains the expected one.
     */
    public function textToContain(string $text): self
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

    // /**
    // * Asserts that the view head has a meta tag with the given attributes array.
    // */
    // public function toHaveMeta(array $attributes): ViewExpectation
    // {
    //    $this->toHave('head');
    //
    //    $properties = implode('][', array_map(
    //        function ($value, $key) {
    //            return sprintf("%s='%s'", $key, $value);
    //        },
    //        $attributes,
    //        array_keys($attributes)
    //    ));
    //
    //    return $this->toHave("meta[{$properties}]");
    // }

    protected function format(string $selector): string
    {
        if (Str::startsWith($selector, '@')) {
            $selector = preg_replace('/@(\\S+)/', '[' . Embuscade::$selectorHtmlAttribute . '="$1"]', $selector);
        }

        return Str::trim($selector);
    }

    /**
     * Returns the node of the current root element.
     */
    protected function getRootElement(): DOMElement
    {
        $node = $this->crawler->getNode(0);

        if ($node === null) {
            throw new RootElementNotFound($this->crawler->outerHtml());
        }
        if (!$node instanceof DOMElement) {
            throw new Exception('Root element is not an instance of DOMElement');
        }

        return $node;
    }

    public function __get(string $name): mixed
    {
        if ($name === 'not') {
            $this->negate = true;

            return $this;
        }

        throw new Exception("Property {$name} does not exist.");
    }

    public function dump(): self
    {
        dump($this->html);

        return $this;
    }

    public function dd(): never
    {
        dd($this->html);
    }
}
