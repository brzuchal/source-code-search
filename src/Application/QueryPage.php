<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

use InvalidArgumentException;

final class QueryPage
{
    /** @var int */
    private $pageNumber;
    /** @var int */
    private $perPageLimit;

    public static function createFromInts(int $pageNumber, int $perPageLimit): self
    {
        if ($pageNumber < 1) {
            throw new InvalidArgumentException("Page number must be positive int, given: {$pageNumber}");
        }
        if ($perPageLimit < 1) {
            throw new InvalidArgumentException("Per queryPage limit must be positive int, given: {$perPageLimit}");
        }

        return new self($pageNumber, $perPageLimit);
    }

    private function __construct(int $pageNumber, int $perPageLimit)
    {
        $this->pageNumber = $pageNumber;
        $this->perPageLimit = $perPageLimit;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function getPerPageLimit(): int
    {
        return $this->perPageLimit;
    }
}
