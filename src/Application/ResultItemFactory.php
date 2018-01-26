<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

final class ResultItemFactory
{
    /** @noinspection MoreThanThreeArgumentsInspection */
    public function createItem(
        string $name,
        string $path,
        string $repositoryName,
        string $repositoryOwner
    ) : ResultItem {
        $repository = ResultItemRepository::createFromStrings($repositoryName, $repositoryOwner);

        return new ResultItem($name, $path, $repository);
    }
}
