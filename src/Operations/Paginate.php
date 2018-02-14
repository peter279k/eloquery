<?php

namespace SehrGut\EloQuery\Operations;

use Illuminate\Database\Query\Builder;
use Psr\Log\InvalidArgumentException;
use SehrGut\EloQuery\Contracts\Operation;

class Paginate implements Operation
{
    /**
     * Number of records per page.
     *
     * @var int
     */
    protected $limit;

    /**
     * Current page number (1-indexed).
     *
     * @var int
     */
    protected $page = 1;

    /**
     * Construct a Paginate instance.
     *
     * @param int $limit
     * @param int $page
     */
    public function __construct(int $limit, int $page = 1)
    {
        if ($limit < 1) {
            throw new InvalidArgumentException('limit must be greater than 0.');
        }

        if ($page < 1) {
            throw new InvalidArgumentException('page must be greater than 0.');
        }

        $this->limit = $limit;
        $this->page = $page;
    }

    /**
     * Apply the pagination constraints to a query builder.
     *
     * @param Builder $builder
     * @return void
     */
    public function applyToBuilder(Builder $builder)
    {
        $builder->limit($this->limit);
        $builder->offset(($this->page - 1) * $this->limit);
    }
}