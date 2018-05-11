<?php

namespace G4\DataRepository;

class WriteRepository
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

    public function write(RepositoryCommand $command)
    {
        if ($this->storageContainer->hasDatasetName()) {
            $this->writeDataMapper($command);
        }

        // RussianDoll::expire
        if ($this->storageContainer->hasRussianDoll()) {
            $this->invalidateRussianDoll($command);
        }

        // IdentityMap::delete
        if ($this->storageContainer->hasIdentityMap()) {
            $this->invalidateIdentityMap($command);
        }
    }

    private function invalidateRussianDoll(RepositoryCommand $command)
    {
        $this
            ->storageContainer
            ->getRussianDoll()
            ->setKey($command->getRussianDollKey())
            ->expire();
    }

    private function invalidateIdentityMap(RepositoryCommand $command)
    {
        $this
            ->storageContainer
            ->getIdentityMap()
            ->delete($command->getIdentityMapKey());
    }

    // TODO Sinisa split each method into a new dedicated class
    private function writeDataMapper(RepositoryCommand $command)
    {
        if ($command->isDelete()) {
            $this
                ->storageContainer
                ->getDataMapper()
                ->delete($command->getIdentity());
        }

        if ($command->isUpsert()) {
            if ($command->getMap()->count() > 1) {
                $mapper = $this->storageContainer->getDataMapperBulk();
                foreach ($command->getMap() as $map) {
                    $mapper->add($map);
                }

                $mapper->upsert();
            } else {
                $map = $command->getMap()->rewind();
                $this
                    ->storageContainer
                    ->getDataMapper()
                    ->upsert($map->current());
            }
        }

        if ($command->isInsert()) {
            if ($command->getMap()->count() > 1) {
                $mapper = $this->storageContainer->getDataMapperBulk();
                foreach ($command->getMap() as $map) {
                    $mapper->add($map);
                }

                $mapper->insert();
            } else {
                $map = $command->getMap()->rewind();
                $this->storageContainer->getDataMapper()->insert($map->current());
            }
        }

        if ($command->isUpdate()) {
            $map = $command->getMap()->rewind();
            $this
                ->storageContainer
                ->getDataMapper()
                ->update($map->current(), $command->getIdentity());
        }

        if ($command->isCustomCommand()) {
            $this
                ->storageContainer
                ->getDataMapper()
                ->query($command->getCustomCommand());
        }
    }
}
