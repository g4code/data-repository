<?php

namespace G4\DataRepository;

class WriteRepository
{
    /**
     * Repository constructor.
     */
    public function __construct(private readonly StorageContainer $storageContainer)
    {
    }

    public function write(RepositoryCommand $command): void
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

    private function invalidateRussianDoll(RepositoryCommand $command): void
    {
        $this
            ->storageContainer
            ->getRussianDoll()
            ->setKey($command->getRussianDollKey())
            ->expire();
    }

    private function invalidateIdentityMap(RepositoryCommand $command): void
    {
        $this
            ->storageContainer
            ->getIdentityMap()
            ->delete($command->getIdentityMapKey());
    }

    // TODO Sinisa split each method into a new dedicated class
    private function writeDataMapper(RepositoryCommand $command): void
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
                $map = $command->getMap();
                $map->rewind();
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
                $map = $command->getMap();
                $map->rewind();
                $this->storageContainer->getDataMapper()->insert($map->current());
            }
        }

        if ($command->isUpdate()) {
            $map = $command->getMap();
            $map->rewind();
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
