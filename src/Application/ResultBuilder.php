<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

use UnexpectedValueException;

final class ResultBuilder
{
    /** @var \Brzuchal\SourceCodeSearch\Application\ResultItemFactory */
    private $itemFactory;
    /** @var ?int */
    private $pageNumber;
    /** @var ?int */
    private $perPageLimit;
    private $items = [];
    private $totalCount = 0;

    public function __construct(ResultItemFactory $itemFactory)
    {
        $this->itemFactory = $itemFactory;
    }

    public function withPageNumber(int $pageNumber): self
    {
        $builder = clone $this;
        $builder->pageNumber = $pageNumber;

        return $builder;
    }

    public function withPerPageLimit(int $perPageLimit): self
    {
        $builder = clone $this;
        $builder->perPageLimit = $perPageLimit;

        return $builder;
    }

    public function withTotalCount(int $totalCount): self
    {
        $builder = clone $this;
        $builder->totalCount = $totalCount;

        return $builder;
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    public function withItem(string $name, string $path, string $repositoryName, string $repositoryOwner): self
    {
        $builder = clone $this;
        $builder->items[] = $this->itemFactory->createItem($name, $path, $repositoryName, $repositoryOwner);

        return $builder;
    }

    public function build(): Result
    {
        if (null === $this->pageNumber) {
            throw new UnexpectedValueException('Unable to build result without pageNumber, use withPageNumber()');
        }
        if (null === $this->perPageLimit) {
            throw new UnexpectedValueException('Unable to build result without perPageLimit, use withPerPageLimit()');
        }
        if (null === $this->totalCount) {
            throw new UnexpectedValueException('Unable to build result without totalCount, use withTotalCount()');
        }

        return new Result(
            $this->items,
            $this->totalCount,
            $this->pageNumber,
            $this->totalCount ? (int)\ceil($this->totalCount / $this->perPageLimit) : 1
        );
    }
}
