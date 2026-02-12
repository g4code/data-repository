<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingStorageException;

class DataRepositoryFactory
{

    private readonly array $storages;

    /**
     * RepositoryFactory constructor.
     * @param array ...$storages
     * @throws MissingStorageException
     */
    public function __construct(...$storages)
    {
        $this->storages = $storages;
    }

    public function create(): DataRepository
    {
        return new DataRepository($this->makeStorageContainer());
    }

    public function makeStorageContainer(): StorageContainer
    {
        return new StorageContainer($this->storages);
    }
}
