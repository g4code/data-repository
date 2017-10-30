<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingStorageException;

class DataRepositoryFactory
{

    /**
     * @var array
     */
    private $storages;

    /**
     * RepositoryFactory constructor.
     * @param array ...$storages
     * @throws MissingStorageException
     */
    public function __construct(...$storages)
    {
        $this->storages = $storages;
    }

    /**
     * @return DataRepository
     */
    public function create()
    {
        return new DataRepository($this->makeStorageContainer());
    }

    /**
     * @return StorageContainer
     */
    public function makeStorageContainer()
    {
        return new StorageContainer($this->storages);
    }
}
