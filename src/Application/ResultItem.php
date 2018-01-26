<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

final class ResultItem
{
    /** @var string */
    private $name;
    /** @var string */
    private $path;
    /** @var ResultItemRepository */
    private $repository;

    public static function createFromStrings(
        string $name,
        string $path,
        string $repositoryName,
        string $repositoryOwner
    ) : self {
        $repository = ResultItemRepository::createFromStrings($repositoryName, $repositoryOwner);

        return new self($name, $path, $repository);
    }

    private function __construct(string $name, string $path, ResultItemRepository $repository)
    {
        $this->name = $name;
        $this->path = $path;
        $this->repository = $repository;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getRepository(): ResultItemRepository
    {
        return $this->repository;
    }
}
