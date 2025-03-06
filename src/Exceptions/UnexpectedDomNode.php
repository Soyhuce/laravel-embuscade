<?php declare(strict_types=1);

namespace Soyhuce\LaravelEmbuscade\Exceptions;

use DOMNode;
use Exception;

class UnexpectedDomNode extends Exception
{
    public function __construct(DOMNode $node)
    {
        $class = $node::class;

        parent::__construct("Expecting a NodeElement, got {$class}.");
    }
}
