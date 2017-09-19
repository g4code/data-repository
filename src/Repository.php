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

    public function read(QueryInterface $query)
    {
        // IdentityMap::has -> IdentityMap::get
        if($this->hasIdentityMap()){
            $data = $this->storageContainer->getIdentityMap()->get($query->getKey());
            if(!empty($data)){
                return $data;
            }
        }

        // RussianDoll::fetch -> IdentityMap::set
        if($this->hasRussianDoll()){
            $data = $this
                ->storageContainer
                ->getRussianDoll()
                ->setKey($query->getKey())
                ->fetch();
            if(!empty($data)){
                $this
                    ->saveIdentityMap($query->getKey(), $data);
                return $data;
            }
        }

        // DataMapper::find -> RussianDoll::write && IdentityMap::set
        if($this->hasDataMapper()){
            $data = $this->storageContainer->getDataMapper()->find($query->getIdentity()); // TODO - add identity
            if(!empty($data)){
                $this
                    ->saveRussianDoll($query->getKey(), $data)
                    ->saveIdentityMap($query->getKey(), $data);
                return $data;
            }
        }

        throw new \Exception('Not found','404'); // TODO - create exception

    }

    public function write(Command $command)
    {

        // DataMapper::update
        if($this->hasDataMapper()){

            if($command->isUpsert()){
                $this
                    ->storageContainer
                    ->getDataMapper()
                    ->upsert($command->getMap());
            }

            if($command->isDelete()){
                $this
                    ->storageContainer
                    ->getDataMapper()
                    ->delete($command->getIdentity());
            }

        }

        // RussianDoll::expire
        if($this->hasRussianDoll()){
            $this
                ->storageContainer
                ->getRussianDoll()
                ->setKey($command->getKey())
                ->expire();
        }

        // IdentityMap::delete
        if($this->hasIdentityMap()){
            $this
                ->storageContainer
                ->getIdentityMap()
                ->delete($command->getKey());
        }

    }

    private function hasRussianDoll()
    {
        return $this->storageContainer->hasRussianDoll();
    }

    private function hasIdentityMap()
    {
        return $this->storageContainer->hasIdentityMap();
    }

    private function hasDataMapper()
    {
        return $this->storageContainer->hasDataMapper();
    }

    private function saveIdentityMap($key, $data)
    {
        if($this->hasIdentityMap()){
            $this
                ->storageContainer
                ->getIdentityMap()
                ->set($key, $data);
        }
        return $this;
    }

    private function saveRussianDoll($key, $data)
    {
        if($this->hasRussianDoll()){
            $this->storageContainer
                ->getRussianDoll()
                ->setKey($key)
                ->write($data);
        }
        return $this;
    }

}