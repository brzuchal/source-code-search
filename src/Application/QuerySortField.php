<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

use Esky\Enum\Enum;

/**
 * @method static QuerySortField BEST_MATCH
 * @method static QuerySortField INDEXED
 */
final class QuerySortField extends Enum
{
    public const BEST_MATCH = 'best-match';
    public const INDEXED = 'indexed';
}
