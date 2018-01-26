<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

interface SearchService
{
    public function find(Query $query): Result;
}
