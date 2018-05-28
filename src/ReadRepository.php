<?php

namespace G4\DataRepository;

use G4\ValueObject\Dictionary;

class ReadRepository
{

    const EMPTY_VALUE = 'EMPTY_VALUE';

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

    public function readNoCache(RepositoryQuery $query)
    {
        $this->query = $query;
        return $this
            ->readFromDataMapper(false)
            ->getResponse();
    }

    private function getResponse()
    {
        return $this->hasResponse()
            ? $this->response
            : (new RepositoryResponseFactory())->createEmptyResponse();
    }

    private function hasResponse()
    {
        return $this->response instanceof DataRepositoryResponse;
    }

    private function readFromIdentityMap()
    {
        if ($this->storageContainer->hasIdentityMap() && !$this->hasResponse()) {
            $data = $this->storageContainer->getIdentityMap()->get($this->query->getIdentityMapKey());
            if ($data === self::EMPTY_VALUE) {
                $this->response = (new RepositoryResponseFactory())->createEmptyResponse();
                return $this;
            }
            if ($this->hasNonEmptyData($data)) {
                $this->response = (new RepositoryResponseFactory())->createFromArray(new Dictionary($data));
            }
        }
        return $this;
    }

    private function readFromRussianDoll()
    {
        if ($this->storageContainer->getRussianDoll() && !$this->hasResponse()) {
            $data = $this
                ->storageContainer
                ->getRussianDoll()
                ->setKey($this->query->getRussianDollKey())
                ->fetch();
            if ($data === self::EMPTY_VALUE) {
                $this->saveIdentityMap($data);
                $this->response = (new RepositoryResponseFactory())->createEmptyResponse();
                return $this;
            }
            if ($this->hasNonEmptyData($data)) {
                $this->saveIdentityMap($data);
                $this->response = (new RepositoryResponseFactory())->createFromArray(new Dictionary($data));
            }
        }
        return $this;
    }

    private function readFromDataMapper($saveToCache = true)
    {
        if ($this->storageContainer->hasDataMapper() && !$this->hasResponse()) {
            $dataMapper = $this->storageContainer->getDataMapper();
            $rawData = $this->query->hasCustomQuery()
                ? $dataMapper->query($this->query->getCustomQuery())
                : $dataMapper->find($this->query->getIdentity());
            $response = (new RepositoryResponseFactory())->create($rawData);

            if ($saveToCache) {
                $data = self::EMPTY_VALUE;
                if ($response->hasData()) {
                    $data = (new RepositoryResponseFormatter($response))->format();
                }
                $this
                    ->saveRussianDoll($data)
                    ->saveIdentityMap($data);
            }
            $this->response = $response;
        }
        return $this;
    }

    private function saveIdentityMap($data)
    {
        if ($this->storageContainer->hasIdentityMap()) {
            $this
                ->storageContainer
                ->getIdentityMap()
                ->set($this->query->getIdentityMapKey(), $data);
        }
        return $this;
    }

    private function saveRussianDoll($data)
    {
        if ($this->storageContainer->hasRussianDoll()) {
            $this->storageContainer
                ->getRussianDoll()
                ->setKey($this->query->getRussianDollKey())
                ->write($data);
        }
        return $this;
    }

    private function hasNonEmptyData($data)
    {
        return !empty($data) && is_array($data);
    }
}
