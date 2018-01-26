<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

use ArrayIterator;
use IteratorAggregate;

final class Result implements IteratorAggregate
{
    /** @var array|ResultItem[] */
    private $items;
    /** @var int */
    private $totalCount;
    /** @var int */
    private $pageNumber;
    /** @var int */
    private $numberOfPages;

    public function __construct(array $items, int $totalCount, int $pageNumber, int $numberOfPages)
    {
        // collection type check at run-time hack
        $this->items = (function (ResultItem ...$items) {
            return $items;
        })(...$items);
        $this->totalCount = $totalCount;
        $this->pageNumber = $pageNumber;
        $this->numberOfPages = $numberOfPages;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
