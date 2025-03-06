<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Concerns;

use Illuminate\Support\Str;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsEmpty;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\StringContains;
use Soyhuce\LaravelEmbuscade\ViewExpectation;

trait HasNodeExpectations
{
    /**
     * Asserts that the current element contains the given attribute value.
     */
    public function toHaveAttribute(string $attribute, ?string $value = null): ViewExpectation
    {
        if ($value === null) {
            $assertion = Assert::logicalNot(new IsNull());
            $message = "Failed asserting that the {$attribute} exists within `{$this->html}`.";
        } else {
            $assertion = new IsIdentical($value);
            $message = "Failed asserting that the {$attribute} `{$value}` exists within `{$this->html}`.";
        }

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = $value === null
                ? "Failed asserting that the {$attribute} does not exist within `{$this->html}`."
                : "Failed asserting that the {$attribute} `{$value}` does not exist within `{$this->html}`.";
        }

        Assert::assertThat($this->crawler->attr($attribute), $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the current element contains the given attribute value.
     */
    public function toHaveAttributeContaining(string $attribute, string $value): ViewExpectation
    {
        $assertion = new StringContains($value);
        $message = "Failed asserting that the {$attribute} contains `{$value}` within `{$this->html}`.";

        if ($this->negate) {
            $this->negate = false;
            $assertion = Assert::logicalNot($assertion);
            $message = "Failed asserting that the {$attribute} does not contain `{$value}` within `{$this->html}`.";
        }

        Assert::assertThat($this->crawler->attr($attribute), $assertion, $message);

        return $this;
    }

    /**
     * Asserts that the current element contains the given class.
     */
    public function toHaveClass(string $class): ViewExpectation
    {
        return $this->toHaveAttributeContaining('class', $class);
    }

    /**
     * Asserts that the current element has a disabled attribute.
     */
    public function toBeDisabled(): ViewExpectation
    {
        return $this->toHaveAttribute('disabled', 'disabled');
    }

    /**
     * Asserts that the current element text is the expected one.
     */
    public function toHaveText(string $text): ViewExpectation
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
    public function toContainText(string $text): ViewExpectation
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
     * Asserts that the current element has no content.
     */
    public function toBeEmpty(): ViewExpectation
    {
        $content = '';
        foreach ($this->crawler->getIterator() as $node) {
            $content .= Str::trim($node->textContent);
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
     * Asserts that the current element contains the given text.
     */
    public function toContain(string $text): ViewExpectation
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
}
