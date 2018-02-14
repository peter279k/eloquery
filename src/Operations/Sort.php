<?php

namespace SehrGut\EloQuery\Operations;

use Illuminate\Database\Query\Builder;
use SehrGut\EloQuery\Contracts\Operation;

class Sort implements Operation
{
    /**
     * The attribute that is sorted by.
     *
     * @var string
     */
    protected $attribute;

    /**
     * Sort direction.
     *
     * @var bool
     */
    protected $ascending = true;

    /**
     * Construct a Sort instance.
     *
     * @param string $attribute
     * @param bool $ascending
     */
    public function __construct(string $attribute, bool $ascending = true)
    {
        $this->attribute = $attribute;
        $this->ascending = $ascending;
    }

    /**
     * Apply the sort order to a query builder.
     *
     * @param Builder $builder
     * @return void
     */
    public function applyToBuilder(Builder $builder)
    {
        $builder->orderBy($this->attribute, $this->getDirection());
    }

    /**
     * Get the sort direction as SQL string.
     *
     * @return string
     */
    private function getDirection() : string
    {
        return $this->ascending ? 'ASC' : 'DESC';
    }
}
