<?php

namespace Harmony\DAL\Illuminate\Reader;

use Harmony\DAL\Read\Query;

class IlluminateQuery implements Query
{
    public function getDataSourceName(): string
    {
        return '';
    }
}
