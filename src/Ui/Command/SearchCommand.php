<?php declare(strict_types=1);
namespace Brzuchal\SourceCodeSearch\Ui\Command;

use Brzuchal\SourceCodeSearch\Application\QueryBuilder;
use Brzuchal\SourceCodeSearch\Application\SearchService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SearchCommand extends Command
{
    /** @var SearchService */
    private $searchService;
    /** @var QueryBuilder */
    private $queryBuilder;
    /** @var string */
    private $sortField;
    /** @var string */
    private $sortOrder;
    /** @var int */
    private $perPageLimit;

    public function __construct(
        SearchService $searchService,
        QueryBuilder $queryBuilder,
        string $sortField,
        string $sortOrder,
        int $perPageLimit
    ) {
        $this->searchService = $searchService;
        $this->queryBuilder = $queryBuilder;
        $this->sortField = $sortField;
        $this->sortOrder = $sortOrder;
        $this->perPageLimit = $perPageLimit;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('search');
        $this->setDescription('Search source code by query');
        $this->addOption(
            'page',
            'p',
            InputOption::VALUE_OPTIONAL,
            'Page number',
            1
        );
        $this->addOption(
            'limit',
            'l',
            InputOption::VALUE_OPTIONAL,
            'Per queryPage limit',
            $this->perPageLimit
        );
        $this->addOption(
            'sort',
            's',
            InputOption::VALUE_OPTIONAL,
            'Sort field (BEST_MATCH, INDEXED)',
            $this->sortField
        );
        $this->addOption(
            'order',
            'o',
            InputOption::VALUE_OPTIONAL,
            'Sort order (ASC, DESC)',
            $this->sortOrder
        );
        $this->addArgument(
            'query',
            InputArgument::IS_ARRAY ^ InputArgument::REQUIRED,
            'Query string'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        try {
            $sortField = $input->getOption('sort');
            if (false === \in_array($sortField, ['BEST_MATCH', 'INDEXED'], true)) {
                throw new \InvalidArgumentException(
                    "Invalid sort field expecting (BEST_MATCH, INDEXED), given: {$sortField}"
                );
            }
            $sortOrder = $input->getOption('order');
            if (false === \in_array($sortOrder, ['ASC', 'DESC'], true)) {
                throw new \InvalidArgumentException(
                    "Invalid sort order expecting (ASC, DESC), given: {$sortOrder}"
                );
            }
            $pageNumber = (int)$input->getOption('page');
            $perPageLimit = (int)$input->getOption('limit');
            $query = $this->queryBuilder
                ->withQuery(\implode(' ', $input->getArgument('query')))
                ->withSort($sortField, $sortOrder)
                ->withPage($pageNumber, $perPageLimit)
                ->build();
            $results = $this->searchService->find($query);
            // @TODO: add CLI formatter
            dump($results);
        } catch (\Exception $exception) {
            $output->writeln("<error>{$exception}</error>");
            return 1;
        }

        return 0;
    }
}
