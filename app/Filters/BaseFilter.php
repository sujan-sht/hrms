<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class BaseFilter
 * @package App\CriteriaFilters
 */
abstract class BaseFilter
{
    /**
     * @var
     */
    private $filters;
    /**
     * @var
     */
    protected $query;

    /**
     * BaseCriteria constructor.
     *
     * @param Builder $query
     * @param array   $filters
     */
    public function __construct(Builder $query, array $filters)
    {
        $this->filters = $filters;
        $this->query   = $query;
    }

    /**
     * @return Builder
     */
    public function apply(): Builder
    {
        foreach ($this->filters as $filter => $value) {
            if ($this->isFilterApplicable($filter)) {
                $this->query = call_user_func_array([$this, $this->getFilterMethodName($filter)], [$value]);
            }
        }

        return $this->query;
    }

    /**
     * @param string $filter
     *
     * @return bool
     */
    private function isFilterApplicable(string $filter): bool
    {
        if (empty(Arr::get($this->filters, $filter))) {
            return false;
        }

        return $this->hasSuitableFilterMethod($filter);
    }

    /**
     * @param string $filter
     *
     * @return bool
     */
    private function hasSuitableFilterMethod(string $filter): bool
    {
        $methodName = $this->getFilterMethodName($filter);

        return method_exists($this, $methodName);
    }

    /**
     * @param string $filter
     *
     * @return string
     */
    private function getFilterMethodName(string $filter): string
    {
        return Str::camel($filter);
    }


    public function filterProcess($query, array $filters)
    {
        foreach ($filters as $column => $value) {
            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $value);
            }
        }
        return $query;
    }
}
