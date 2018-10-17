<?php

namespace Harmony\DAL\Illuminate;

use Harmony\DAL\Model;

class ConnectionFacade
{
    public static function getTableName(Model $model): string
    {
        preg_match_all(
            '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!',
            (new \ReflectionClass($model))->getShortName(),
            $matches
        );

        return strtolower(implode('_', $matches[0]));
    }
}
