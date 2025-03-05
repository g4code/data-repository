<?php

namespace G4\DataRepository;

use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\RussianDoll\Key;

class DataRepository
{
    final public const DELIMITER = "|";

    private ?IdentityInterface $identity = null;

    private ?string $identityMapKey = null;

    private ?Key $russianDollKey = null;

    private ?MapperCollection $map = null;

    /**
     * Repository constructor.
     */
    public function __construct(private readonly StorageContainer $storageContainer)
    {
    }

    public function setDatasetName(string $datasetName): self
    {
        $this->storageContainer->setDatasetName($datasetName);
        return $this;
    }

    public function setIdentity(IdentityInterface $identity): self
    {
        $this->identity = $identity;
        return $this;
    }

    public function setRussianDollKey(Key $russianDollKey): self
    {
        $this->russianDollKey = $russianDollKey;
        $this->identityMapKey = (string) $russianDollKey;
        return $this;
    }

    public function setIdentityMapKey(...$identityMapKey): self
    {
        $this->identityMapKey = implode(self::DELIMITER, $identityMapKey);
        return $this;
    }

    public function select(): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        $query = new RepositoryQuery();

        if ($this->getRussianDollKey() instanceof Key) {
            $query->setRussianDollKey($this->getRussianDollKey());
        }

        if ($this->getIdentity() instanceof IdentityInterface) {
            $query->setIdentity($this->getIdentity());
        }

        if (!empty($this->identityMapKey)) {
            $query->setIdentityMapKey($this->getIdentityMapKey());
        }

        return (new ReadRepository($this->storageContainer))->read($query);
    }

    public function query($customQuery): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        $query = new RepositoryQuery();
        $query->setCustomQuery($customQuery);
        if ($this->getRussianDollKey() instanceof Key) {
            $query->setRussianDollKey($this->getRussianDollKey());
        }

        if (!empty($this->identityMapKey)) {
            $query->setIdentityMapKey($this->getIdentityMapKey());
        }

        return (new ReadRepository($this->storageContainer))->read($query);
    }

    public function queryNoCache($customQuery): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        $query = new RepositoryQuery();
        $query->setCustomQuery($customQuery);
        return (new ReadRepository($this->storageContainer))->readNoCache($query);
    }

    public function simpleQuery($customQuery): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        $query = new RepositoryQuery();
        $query->setCustomQuery($customQuery);

        return (new ReadRepository($this->storageContainer))->simpleQuery($query);
    }

    public function command($customCommand): void
    {
        $command = $this->getCommand();
        $command->setCustomCommand($customCommand);
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function setMapping(MappingInterface ...$maps): self
    {
        $this->map = new MapperCollection($maps);
        return $this;
    }

    public function insert(): void
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
        } catch (\Exception) {
            return null;
        }
    }

    public function update(): void
    {
        $command = $this->getCommand();
        $command->update($this->map, $this->getIdentity());
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function upsert(): void
    {
        $command = $this->getCommand();
        $command->upsert($this->map);
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function delete(): void
    {
        $command = $this->getCommand();
        $command->delete($this->getIdentity());
        (new WriteRepository($this->storageContainer))->write($command);
    }

    public function getIdentity(): ?IdentityInterface
    {
        return $this->identity;
    }

    public function getIdentityMapKey(): ?string
    {
        return $this->identityMapKey;
    }

    public function getRussianDollKey(): ?Key
    {
        return $this->russianDollKey;
    }

    private function getCommand(): RepositoryCommand
    {
        $command = new RepositoryCommand();

        if ($this->getRussianDollKey() instanceof Key) {
            $command->setRussianDollKey($this->getRussianDollKey());
        }

        if (!empty($this->identityMapKey)) {
            $command->setIdentityMapKey($this->getIdentityMapKey());
        }
        return $command;
    }
}
