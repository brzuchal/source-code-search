<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

use ArrayIterator;
use IteratorAggregate;

final class Result implements IteratorAggregate
{
    private $results = [];

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->results);
    }
}
