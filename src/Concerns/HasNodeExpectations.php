<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Concerns;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\StringContains;
use Soyhuce\LaravelEmbuscade\ViewExpect;

trait HasNodeExpectations
{
    /**
     * Asserts that the current element contains the given attribute value.
     */
    public function toHaveAttribute(string $attribute, ?string $value = null): ViewExpect
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
    public function toHaveAttributeContaining(string $attribute, string $value): ViewExpect
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
    public function toHaveClass(string $class): ViewExpect
    {
        return $this->toHaveAttributeContaining('class', $class);
    }

    /**
     * Asserts that the current element has expected accept attribute.
     */
    public function toAccept(string $accept): ViewExpect
    {
        return $this->toHaveAttribute('accept', $accept);
    }

    /**
     * Asserts that the current element has a disabled attribute.
     */
    public function toBeDisabled(): ViewExpect
    {
        return $this->toHaveAttribute('disabled');
    }

    /**
     * Asserts that the current element has expected alt attribute.
     */
    public function toHaveAlt(string $alt): ViewExpect
    {
        return $this->toHaveAttribute('alt', $alt);
    }

    /**
     * Asserts that the current element has expected href attribute.
     */
    public function toHaveHref(string $href): ViewExpect
    {
        return $this->toHaveAttribute('href', $href);
    }

    /**
     * Asserts that the current element has expected src attribute.
     */
    public function toHaveSrc(string $src): ViewExpect
    {
        return $this->toHaveAttribute('src', $src);
    }

    /**
     * Asserts that the current element has expected value attribute.
     */
    public function toHaveValue(string $value): ViewExpect
    {
        return $this->toHaveAttribute('value', $value);
    }
}
