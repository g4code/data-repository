<?php

namespace G4\DataRepository;

use \G4\DataMapper\Common\MappingInterface;

class Repository
{
    const DELIMITER = "|";

    /**
     * @var StorageContainer
     */
    private $storageContainer;

    /*
     * @var \G4\DataMapper\Common\IdentityInterface
     */
    private $identity;

    private $identityMapKey;

    /*
     * @var \G4\RussianDoll\Key
     */
    private $russianDollKey;

    /*
     * @return MappingInterface
     */
    private $map;

    /**
     * Repository constructor.
     * @param StorageContainer $storageContainer
     */
    public function __construct(StorageContainer $storageContainer)
    {
        $this->storageContainer = $storageContainer;
    }


    public function getIdentity()
    {
        return $this->identity;
    }


    public function getIdentityMapKey()
    {
        return $this->identityMapKey;
    }

    public function getRussianDollKey()
    {
        return $this->russianDollKey;
    }

//    public function read(RepositoryQuery $query)
//    {
//        // IdentityMap::has -> IdentityMap::get
//        return (new ReadRepository($this->storageContainer))->read($query);
//    }

    public function setDatasetName($datasetName)
    {
        $this->storageContainer->makeDataMapper($datasetName);
        return $this;
    }

    public function setIdentity(\G4\DataMapper\Common\IdentityInterface $identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function setRussianDollKey(\G4\RussianDoll\Key $russianDollKey)
    {
        $this->russianDollKey = $russianDollKey;
        return $this;
    }

    public function setIdentityMapKey(...$identityMapKey)
    {
        $this->identityMapKey = join(self::DELIMITER, $identityMapKey);
        return $this;
    }

    /*
     * @return RepositoryResponse
     */
    public function select()
    {
        $query = new RepositoryQuery();

        if($this->getRussianDollKey() instanceof \G4\RussianDoll\Key) {
            $query->setRussianDollKey($this->getRussianDollKey());
        }

        if($this->getIdentity() instanceof \G4\DataMapper\Common\IdentityInterface){
            $query->setIdentity($this->getIdentity());
        }

        if(!empty($this->identityMapKey)){
            $query->setIdentityMapKey($this->getIdentityMapKey());
        }

        return (new ReadRepository($this->storageContainer))->read($query);
    }

    public function setMapping(MappingInterface $map)
    {
        $this->map = $map;
        return $this;
    }

    public function insert()
    {
        $command = $this->getCommand();
        $command->insert($this->map);
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function update()
    {
        $command = $this->getCommand();
        $command->update($this->map, $this->getIdentity());
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function upsert()
    {
        $command = $this->getCommand();
        $command->upsert($this->map);
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function delete()
    {
        $command = $this->getCommand();
        $command->delete($this->getIdentity());
        (new WriteRepository($this->storageContainer))->write($command);
    }

    private function getCommand()
    {
        $command = new RepositoryCommand();

        if($this->getRussianDollKey() instanceof \G4\RussianDoll\Key) {
            $command->setRussianDollKey($this->getRussianDollKey());
        }

        if(!empty($this->identityMapKey)){
            $command->setIdentityMapKey($this->getIdentityMapKey());
        }
        return $command;
    }
//    public function write(RepositoryCommand $command)
//    {
//        (new WriteRepository($this->storageContainer))->write($command);
//    }

}