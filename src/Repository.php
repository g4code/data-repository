<?php

namespace G4\DataRepository;


class Repository
{

    /**
     * @var StorageContainer
     */
    private $storageContainer;

    /**
     * Repository constructor.
     * @param StorageContainer $storageContainer
     */
    public function __construct(StorageContainer $storageContainer)
    {
        $this->storageContainer = $storageContainer;
    }

    public function read(RepositoryQuery $query)
    {
        // IdentityMap::has -> IdentityMap::get
        return (new ReadRepository($this->storageContainer))->read($query);
    }

    public function write(RepositoryCommand $command)
    {
        (new WriteRepository($this->storageContainer))->write($command);
    }

}