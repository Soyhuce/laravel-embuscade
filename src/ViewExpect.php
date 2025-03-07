<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade;

use Exception;
use Illuminate\Support\Traits\Macroable;
use Soyhuce\LaravelEmbuscade\Concerns\FormatsSelectors;
use Soyhuce\LaravelEmbuscade\Concerns\HasNodeExpectations;
use Soyhuce\LaravelEmbuscade\Concerns\HasViewExpectations;
use Soyhuce\LaravelEmbuscade\Concerns\SelectsElements;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property-read ViewExpect $not
 */
class ViewExpect
{
    use FormatsSelectors;
    use HasNodeExpectations;
    use HasViewExpectations;
    use Macroable;
    use SelectsElements;

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
