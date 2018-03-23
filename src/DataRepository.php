<?php

namespace G4\DataRepository;

use \G4\DataMapper\Common\MappingInterface;

class DataRepository
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

    /**
     * @var MapperCollection
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

    /**
     * @param $datasetName
     * @return $this
     */
    public function setDatasetName($datasetName)
    {
        $this->storageContainer->setDatasetName($datasetName);
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
        $this->identityMapKey = (string) $russianDollKey;
        return $this;
    }

    public function setIdentityMapKey(...$identityMapKey)
    {
        $this->identityMapKey = implode(self::DELIMITER, $identityMapKey);
        return $this;
    }

    /*
     * @return RepositoryResponse
     */
    public function select()
    {
        $query = new RepositoryQuery();

        if ($this->getRussianDollKey() instanceof \G4\RussianDoll\Key) {
            $query->setRussianDollKey($this->getRussianDollKey());
        }

        if ($this->getIdentity() instanceof \G4\DataMapper\Common\IdentityInterface) {
            $query->setIdentity($this->getIdentity());
        }

        if (!empty($this->identityMapKey)) {
            $query->setIdentityMapKey($this->getIdentityMapKey());
        }

        return (new ReadRepository($this->storageContainer))->read($query);
    }

    public function query($customQuery)
    {
        $query = new RepositoryQuery();
        $query->setCustomQuery($customQuery);
        if ($this->getRussianDollKey() instanceof \G4\RussianDoll\Key) {
            $query->setRussianDollKey($this->getRussianDollKey());
        }

        if (!empty($this->identityMapKey)) {
            $query->setIdentityMapKey($this->getIdentityMapKey());
        }

        return (new ReadRepository($this->storageContainer))->read($query);
    }

    public function queryNoCache($customQuery)
    {
        $query = new RepositoryQuery();
        $query->setCustomQuery($customQuery);
        return (new ReadRepository($this->storageContainer))->readNoCache($query);
    }

    public function command($customCommand)
    {
        $command = $this->getCommand();
        $command->setCustomCommand($customCommand);
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function setMapping(MappingInterface ...$maps)
    {
        $this->map = new MapperCollection($maps);
        return $this;
    }

    public function insert()
    {
        $command = $this->getCommand();
        $command->insert($this->map);
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function insertId()
    {
        try {
            $insertId = $this->queryNoCache('SELECT LAST_INSERT_ID() AS LAST_INSERT_ID')->getOne();
            return $insertId['LAST_INSERT_ID'];
        } catch (\Exception $e) {
            return null;
        }
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

    private function getCommand()
    {
        $command = new RepositoryCommand();

        if ($this->getRussianDollKey() instanceof \G4\RussianDoll\Key) {
            $command->setRussianDollKey($this->getRussianDollKey());
        }

        if (!empty($this->identityMapKey)) {
            $command->setIdentityMapKey($this->getIdentityMapKey());
        }
        return $command;
    }
}
