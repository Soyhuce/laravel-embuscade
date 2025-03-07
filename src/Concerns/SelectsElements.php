<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Concerns;

use Illuminate\Support\Collection;
use Soyhuce\LaravelEmbuscade\ViewExpect;
use Symfony\Component\DomCrawler\Crawler;

trait SelectsElements
{
    /**
     * Creates a new view assertion with the given selector.
     *
     * @param callable(ViewExpect): void|null $callback
     */
    public function in(string $selector, ?callable $callback = null): ViewExpect
    {
        if ($callback !== null) {
            $callback($this->in($selector));

            return $this;
        }

        $selector = $this->format($selector);

        $this->toHave($selector);

        $filteredHtml = $this->crawler
            ->children()
            ->filter($selector)
            ->each(fn (Crawler $node): string => $node->outerHtml());

        return new ViewExpect(new Collection($filteredHtml)->implode(''));
    }

    /**
     * Creates a new view assertion with the given selector at the given position.
     *
     * @param callable(ViewExpect): void|null $callback
     */
    public function at(string $selector, int $position, ?callable $callback = null): ViewExpect
    {
        if ($callback !== null) {
            $callback($this->at($selector, $position));

            return $this;
        }

        $selector = $this->format($selector);

        $node = $this->crawler->filter($selector)->eq($position);

        return new ViewExpect($node->outerHtml());
    }

    /**
     * Creates a new view assertion with the given selector at the first position.
     *
     * @param callable(ViewExpect): void|null $callback
     */
    public function first(string $selector, ?callable $callback = null): ViewExpect
    {
        if ($callback !== null) {
            $callback($this->first($selector));

            return $this;
        }

        $selector = $this->format($selector);

        $node = $this->crawler->filter($selector)->first();

        return new ViewExpect($node->outerHtml());
    }

    /**
     * Creates a new view assertion with the given selector at the last position.
     *
     * @param callable(ViewExpect): void|null $callback
     */
    public function last(string $selector, ?callable $callback = null): ViewExpect
    {
        if ($callback !== null) {
            $callback($this->last($selector));

            return $this;
        }

        $selector = $this->format($selector);

        $node = $this->crawler->filter($selector)->last();

        return new ViewExpect($node->outerHtml());
    }

    /**
     * Creates a new view assertion with the given unique selector.
     *
     * @param callable(ViewExpect): void|null $callback
     */
    public function sole(string $selector, ?callable $callback = null): ViewExpect
    {
        if ($callback !== null) {
            $callback($this->sole($selector));

            return $this;
        }

        $selector = $this->format($selector);

        $this->toHave($selector, 1);

        $node = $this->crawler->filter($selector)->first();

        return new ViewExpect($node->outerHtml());
    }
}
