<?php

namespace Harmony\DAL\Illuminate\Reader;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Harmony\DAL\Exception\NotFoundException;
use Harmony\DAL\Model;
use Harmony\DAL\Model\Hydration\Hydrator;
use Harmony\DAL\Read\Query;
use Harmony\DAL\Read\Reader;
use Illuminate\Database\ConnectionInterface;

class IlluminateReader implements Reader
{
    private $connection;
    private $hydrator;
    private $queryBuilder;

    public function __construct(ConnectionInterface $connection, Hydrator $hydrator, QueryBuilder $queryBuilder)
    {
        $this->connection = $connection;
        $this->hydrator = $hydrator;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param IlluminateQuery|Query $query
     * @return Collection|Model[]
     */
    public function get(Query $query): Collection
    {
        return (new ArrayCollection(
            $this->queryBuilder->buildQuery($this->connection->table($query->getDataSourceName()), $query)
                ->get()
                ->toArray()
        ))->map(function (object $record) {
            return $this->hydrator->hydrate($record);
        });
    }

    /**
     * @param Query $query
     * @return Model
     *
     * @throws NotFoundException
     *  Must be thrown if no record matching the provided query is found
     */
    public function getFirst(Query $query): Model
    {
        $data = $this->queryBuilder->buildQuery($this->connection->table($query->getDataSourceName()), $query)->first();

        if (!$data) {
            throw new NotFoundException;
        }

        return $this->hydrator->hydrate((object) $data);
    }

    /**
     * @param Query $query
     * @return Model
     *
     * @throws NotFoundException
     *  Must be thrown if no record matching the provided query is found
     */
    public function getLast(Query $query): Model
    {
        $data = $this->queryBuilder->buildQuery($this->connection->table($query->getDataSourceName()), $query)->latest();

        if (!$data) {
            throw new NotFoundException;
        }

        return $this->hydrator->hydrate((object) $data);
    }
}
