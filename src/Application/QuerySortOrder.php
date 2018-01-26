<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Application;

use Esky\Enum\Enum;

/**
 * @method static QuerySortOrder ASC
 * @method static QuerySortOrder DESC
 */
final class QuerySortOrder extends Enum
{
    public const ASC = 'asc';
    public const DESC = 'desc';
}
