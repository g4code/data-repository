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
        // DataMapper::update
        if($this->storageContainer->hasDataMapper()){
            $this->writeDataMapper($command);
        }

        // RussianDoll::expire
        if($this->storageContainer->hasRussianDoll()){
            $this->invalidateRussianDoll($command);
        }

        // IdentityMap::delete
        if($this->storageContainer->hasIdentityMap()){
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

    private function writeDataMapper(RepositoryCommand $command)
    {
        if($command->isDelete()){
            $this
                ->storageContainer
                ->getDataMapper()
                ->delete($command->getIdentity());
        }

        if($command->isUpsert()){
            $this
                ->storageContainer
                ->getDataMapper()
                ->upsert($command->getMap());
        }

        if($command->isInsert()){
            $this
                ->storageContainer
                ->getDataMapper()
                ->insert($command->getMap());
        }

        if($command->isUpdate()){
            $this
                ->storageContainer
                ->getDataMapper()
                ->update($command->getMap(), $command->getIdentity());
        }

        if($command->isCustomCommand()){
            $this
                ->storageContainer
                ->getDataMapper()
                ->query($command->getCustomCommand());
        }

    }

}