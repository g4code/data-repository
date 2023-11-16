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

    private ?\G4\IdentityMap\IdentityMap $identityMap = null;

    /**
     * @var MapperInterface
     */
    private $dataMapper;

    /**
     * @var Bulk
     */
    private $dataMapperBulk;

    private ?\G4\RussianDoll\RussianDoll $russianDoll = null;

    /*
     * @var Builder
     */
    private ?\G4\DataMapper\Builder $dataMapperBuilder = null;

    private $datasetName;

    /**
     * StorageContainer constructor.
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
    }

    public function getIdentityMap(): ?IdentityMap
    {
        return $this->identityMap;
    }

    /**
     * @throws MissingDatasetNameValueException
     */
    public function getDataMapper(): MapperInterface
    {
        if (!$this->dataMapper instanceof MapperInterface) {
            if ($this->datasetName === null) {
                throw new MissingDatasetNameValueException();
            }

            $this->dataMapper = $this->makeDataMapper();
        }

        return $this->dataMapper;
    }

    /**
     * @return Bulk
     * @throws MissingDatasetNameValueException
     */
    public function getDataMapperBulk(): Bulk
    {
        if (!$this->dataMapperBulk instanceof Bulk) {
            if ($this->datasetName === null) {
                throw new MissingDatasetNameValueException();
            }

            $this->dataMapperBulk = $this->makeDataMapperBulk();
        }

        return $this->dataMapperBulk;
    }

    public function setDatasetName(string $datasetName)
    {
        $this->datasetName = $datasetName;
        return $this;
    }

    public function getRussianDoll(): ?RussianDoll
    {
        return $this->russianDoll;
    }

    public function hasIdentityMap(): bool
    {
        return $this->identityMap instanceof IdentityMap;
    }

    public function hasDataMapper(): bool
    {
        return $this->getDataMapper() instanceof MapperInterface;
    }

    public function hasDataMapperBulk(): bool
    {
        return $this->getDataMapperBulk() instanceof Bulk;
    }

    public function hasDatasetName(): bool
    {
        return $this->datasetName !== null;
    }

    public function hasDataMapperBuilder(): bool
    {
        return $this->dataMapperBuilder instanceof Builder;
    }

    public function hasRussianDoll(): bool
    {
        return $this->russianDoll instanceof RussianDoll;
    }

    /**
     * @throws NotValidStorageException
     */
    private function addStorage(mixed $aStorage): void
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

    private function makeDataMapper(): MapperInterface
    {
        return $this->dataMapperBuilder
            ->collectionName(new MySQLTableName($this->datasetName))
            ->buildMapper();
    }

    private function makeDataMapperBulk(): Bulk
    {
        return $this->dataMapperBuilder
            ->collectionName(new MySQLTableName($this->datasetName))
            ->buildBulk();
    }
}
