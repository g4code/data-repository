<?php

namespace G4\DataRepository;

use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Engine\MySQL\MySQLTableName;
use G4\IdentityMap\IdentityMap;
use G4\DataRepository\Exception\MissingStorageException;
use G4\DataRepository\Exception\NotValidStorageException;
use G4\RussianDoll\RussianDoll;
use G4\DataMapper\Builder;

class StorageContainer
{

    /**
     * @var IdentityMap
     */
    private $identityMap;

    /**
     * @var MapperInterface
     */
    private $dataMapper;

    /**
     * @var RussianDoll
     */
    private $russianDoll;

    /*
     * @var Builder
     */
    private $dataMapperBuilder;

    /**
     * StorageContainer constructor.
     * @param array $storages
     */
    public function __construct(array $storages)
    {
        if (empty($storages)) {
            throw new MissingStorageException();
        }
        foreach($storages as $aStorage) {
            $this->addStorage($aStorage);
        }
    }

    /**
     * @return IdentityMap
     */
    public function getIdentityMap()
    {
        return $this->identityMap;
    }

    /**
     * @return MapperInterface
     */
    public function getDataMapper()
    {
        return $this->dataMapper;
    }
    /*
     * @return Builder
     */
    public function makeDataMapper($datasetName)
    {
        $this->dataMapper = $this->dataMapperBuilder->collectionName(new MySQLTableName($datasetName))->buildMapper();
        return $this;
    }

    /**
     * @return RussianDoll
     */
    public function getRussianDoll()
    {
        return $this->russianDoll;
    }

    /**
     * @return bool
     */
    public function hasIdentityMap()
    {
        return $this->identityMap instanceof IdentityMap;
    }

    /**
     * @return bool
     */
    public function hasDataMapper()
    {
        return $this->dataMapper instanceof MapperInterface;
    }

    /**
     * @return bool
     */
    public function hasDataMapperBuoder()
    {
        return $this->dataMapperBuilder instanceof Builder;
    }

    /**
     * @return bool
     */
    public function hasRussianDoll()
    {
        return $this->russianDoll instanceof RussianDoll;
    }

    /**
     * @param mixed $aStorage
     * @throws NotValidStorageException
     */
    private function addStorage($aStorage)
    {
        if ($aStorage instanceof IdentityMap) {
            $this->identityMap = $aStorage;
            return;
        }
        if ($aStorage instanceof RussianDoll) {
            $this->russianDoll = $aStorage;
            return;
        }

        if ($aStorage instanceof Builder) {
            $this->dataMapperBuilder = $aStorage;
            return;
        }
        throw new NotValidStorageException();
    }
}