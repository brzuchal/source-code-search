<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

final class QueryString
{
    private const REGEX = '/^([0-9a-z\s\.\,\<\>\{\}\[\]\+\*\_\-\!\@\$\%\&\(\):\;\'\"\/\\\\]+)$/i';
    /** @var string */
    private $value;

    public static function fromString(string $value): self
    {
        if (\preg_match(self::REGEX, $value)) {
            return new self($value);
        }
        throw new \InvalidArgumentException("Invalid query string, given: {$value}");
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
