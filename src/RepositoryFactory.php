<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingStorageException;

class RepositoryFactory
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
     * @return Repository
     */
    public function create()
    {
        return new Repository($this->makeStorageContainer());
    }

    /**
     * @return StorageContainer
     */
    public function makeStorageContainer()
    {
        return new StorageContainer($this->storages);
    }

}