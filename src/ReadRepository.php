<?php

namespace G4\DataRepository;


use G4\ValueObject\Dictionary;

class ReadRepository
{

    /**
     * @var StorageContainer
     */
    private $storageContainer;

    /*
     * @return DataRepositoryResponse
     */
    private $response;

    /*
     * @return RepositoryQuery
     */
    private $query;

    /**
     * Repository constructor.
     * @param StorageContainer $storageContainer
     */
    public function __construct(StorageContainer $storageContainer)
    {
        $this->storageContainer = $storageContainer;
    }

    public function read(RepositoryQuery $query)
    {
        $this->query = $query;
        return $this
            ->readFromIdentityMap()
            ->readFromRussianDoll()
            ->readFromDataMapper()
            ->getResponse();
    }

    private function getResponse()
    {
        if($this->hasResponse()){
            return $this->response;
        }
        throw new \Exception('Not found','404'); // TODO - create exception
    }

    private function hasResponse()
    {
        return $this->response instanceof DataRepositoryResponse;
    }

    private function readFromIdentityMap()
    {
        if($this->storageContainer->hasIdentityMap() && !$this->hasResponse()){
            $data = $this->storageContainer->getIdentityMap()->get($this->query->getIdentityMapKey());
            if(!empty($data)){
                $this->response = (new RepositoryResponseFactory())->createFromArray(new Dictionary($data));
            }
        }
        return $this;
    }

    private function readFromRussianDoll()
    {
        if($this->storageContainer->getRussianDoll() && !$this->hasResponse()){
            $data = $this
                ->storageContainer
                ->getRussianDoll()
                ->setKey($this->query->getRussianDollKey())
                ->fetch();
            if(!empty($data)){
                $this->saveIdentityMap($data);
                $this->response = (new RepositoryResponseFactory())->createFromArray(new Dictionary($data));
            }
        }
        return $this;
    }

    private function readFromDataMapper()
    {
        if($this->storageContainer->hasDataMapper() && !$this->hasResponse()){
            $rawData = $this->storageContainer->getDataMapper()->find($this->query->getIdentity());
            $response = (new RepositoryResponseFactory())->create($rawData);
            if($response->hasData()){
                $data = (new RepositoryResponseFormatter($response))->format();
                $this
                    ->saveRussianDoll($data)
                    ->saveIdentityMap($data);
                $this->response = $response;
            }
        }
        return $this;
    }

    private function saveIdentityMap($data)
    {
        if($this->storageContainer->hasIdentityMap()){
            $this
                ->storageContainer
                ->getIdentityMap()
                ->set($this->query->getIdentityMapKey(), $data);
        }
        return $this;
    }

    private function saveRussianDoll($data)
    {
        if($this->storageContainer->hasRussianDoll()){
            $this->storageContainer
                ->getRussianDoll()
                ->setKey($this->query->getRussianDollKey())
                ->write($data);
        }
        return $this;
    }

}