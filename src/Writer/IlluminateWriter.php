<?php

namespace Harmony\DAL\Illuminate\Writer;

use Harmony\DAL\Illuminate\ConnectionFacade;
use Harmony\DAL\Model;
use Harmony\DAL\Model\Hydration\Extractor;
use Harmony\DAL\Write\Writer;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;

class IlluminateWriter implements Writer
{
    private $connection;
    private $extractor;
    private $table;

    public function __construct(?string $table, ConnectionInterface $connection, Extractor $extractor)
    {
        $this->connection = $connection;
        $this->extractor = $extractor;
        $this->table = $table;
    }

    public function create(Model $model): void
    {
        $this->table($model)->insert((array) $this->extractor->extract($model));
    }

    public function delete(Model $model): void
    {
        $id = (string) $model->getIdentifier();

        $this->table($model)->delete(is_numeric($id) ? (int) $id : $id);
    }

    public function update(Model $original, Model $new): void
    {
        $table = $this->table($original);
        $id = (string) $original->getIdentifier();

        $original = (array) $this->extractor->extract($original);
        $new = (array) $this->extractor->extract($new);

        $table->where('id', '=', is_numeric($id) ? (int) $id : $id)
            ->update(array_diff_assoc($new, $original));
    }

    private function table(Model $model): Builder
    {
        if (!is_null($this->table)) {
            return $this->connection->table($this->table);
        }

        return $this->connection->table(ConnectionFacade::getTableName($model));
    }
}
