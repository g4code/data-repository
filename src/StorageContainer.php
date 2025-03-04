<?php

namespace G4\DataRepository;

use G4\DataMapper\Common\Bulk;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Engine\MySQL\MySQLTableName;
use G4\IdentityMap\IdentityMap;
use G4\DataRepository\Exception\MissingStorageException;
use G4\DataRepository\Exception\NotValidStorageException;
use G4\RussianDoll\RussianDoll;
use G4\DataMapper\Builder;
use G4\DataRepository\Exception\MissingDatasetNameValueException;

class StorageContainer
{

    /**
     * @var IdentityMap
     */
    private $identityMap;

    /** @var array<string, MapperInterface> */
    private $dataMappers;

    /** @var array<string, Bulk> */
    private $dataMappersBulk;

    /**
     * @var RussianDoll
     */
    private $russianDoll;

    /*
     * @var Builder
     */
    private $dataMapperBuilder;

    private $datasetName;

    /**
     * StorageContainer constructor.
     * @param array $storages
     * @throws MissingStorageException
     */
    public function __construct(array $storages)
    {
        if (empty($storages)) {
            throw new MissingStorageException();
        }
        foreach ($storages as $aStorage) {
            $this->addStorage($aStorage);
        }

        $this->dataMappers = [];
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
     * @throws MissingDatasetNameValueException
     */
    public function getDataMapper()
    {
        if ($this->datasetName === null) {
            throw new MissingDatasetNameValueException();
        }

        if(!isset($this->dataMappers[$this->datasetName])) {
            $this->dataMappers[$this->datasetName] = $this->makeDataMapper();
        }

        return $this->dataMappers[$this->datasetName];
    }

    /**
     * @return Bulk
     * @throws MissingDatasetNameValueException
     */
    public function getDataMapperBulk()
    {
        if ($this->datasetName === null) {
            throw new MissingDatasetNameValueException();
        }

        if(!isset($this->dataMappersBulk[$this->datasetName])) {
            $this->dataMappersBulk[$this->datasetName] = $this->makeDataMapperBulk();
        }

        return $this->dataMappersBulk[$this->datasetName];
    }

    /**
     * @param $datasetName
     * @return $this
     */
    public function setDatasetName($datasetName)
    {
        $this->datasetName = $datasetName;
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
        return $this->getDataMapper() instanceof MapperInterface;
    }

    /**
     * @return bool
     */
    public function hasDataMapperBulk()
    {
        return $this->getDataMapperBulk() instanceof Bulk;
    }

    /**
     * @return bool
     */
    public function hasDatasetName()
    {
        return $this->datasetName !== null;
    }

    /**
     * @return bool
     */
    public function hasDataMapperBuilder()
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

    /**
     * @return MapperInterface
     */
    private function makeDataMapper()
    {
        return $this->dataMapperBuilder
            ->collectionName(new MySQLTableName($this->datasetName))
            ->buildMapper();
    }

    /**
     * @return Bulk
     */
    private function makeDataMapperBulk()
    {
        return $this->dataMapperBuilder
            ->collectionName(new MySQLTableName($this->datasetName))
            ->buildBulk();
    }
}
