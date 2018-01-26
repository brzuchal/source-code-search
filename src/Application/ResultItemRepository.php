<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

final class ResultItemRepository
{
    /** @var string */
    private $name;
    /** @var string */
    private $owner;

    public static function createFromStrings(string $name, string $owner)
    {
        return new self($name, $owner);
    }

    private function __construct(string $name, string $owner)
    {
        $this->name = $name;
        $this->owner = $owner;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }
}
