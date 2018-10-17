<?php

namespace Harmony\DAL\Illuminate\Reader;

use Harmony\DAL\Read\Query;
use Illuminate\Database\Query\Builder;

interface QueryBuilder
{
    /**
     * The \Harmony\DAL\Illuminate\Reader\IlluminateReader will pass a \Illuminate\Database\Query\Builder object
     * into this method along with the provided \Harmony\DAL\Read\Query object. You can use this query object to
     * perform specific actions on the Builder, such as defining specific Builder::where calls
     *
     * @param Builder $builder
     * @param Query $query
     * @return Builder
     */
    public function buildQuery(Builder $builder, Query $query): Builder;
}
