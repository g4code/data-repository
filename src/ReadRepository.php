<?php

namespace G4\DataRepository;

use G4\ValueObject\Dictionary;

class ReadRepository
{

    final public const EMPTY_VALUE = 'EMPTY_VALUE';

    /**
     * @var SimpleDataRepositoryResponse|DataRepositoryResponse
     */
    private $response;

    private ?RepositoryQuery $query = null;

    /**
     * Repository constructor.
     */
    public function __construct(private readonly StorageContainer $storageContainer)
    {
    }

    public function read(RepositoryQuery $query): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        $this->query = $query;
        return $this
            ->readFromIdentityMap()
            ->readFromRussianDoll()
            ->readFromDataMapper()
            ->getResponse();
    }

    public function readNoCache(RepositoryQuery $query): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        $this->query = $query;
        return $this
            ->readFromDataMapper(false)
            ->getResponse();
    }

    public function simpleQuery(RepositoryQuery $query): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        $this->query = $query;

        return $this
            ->execSimpleQuery()
            ->getSimpleResponse();
    }

    private function getSimpleResponse(): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        return $this->hasSimpleResponse()
            ? $this->response
            : (new RepositoryResponseFactory())->createSimpleEmptyResponse();
    }

    private function hasSimpleResponse(): bool
    {
        return $this->response instanceof SimpleDataRepositoryResponse;
    }

    private function getResponse(): SimpleDataRepositoryResponse | DataRepositoryResponse
    {
        return $this->hasResponse()
            ? $this->response
            : (new RepositoryResponseFactory())->createEmptyResponse();
    }

    private function hasResponse(): bool
    {
        return $this->response instanceof DataRepositoryResponse;
    }

    private function readFromIdentityMap(): self
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

    private function readFromRussianDoll(): self
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

    private function readFromDataMapper($saveToCache = true): self
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

    private function execSimpleQuery(): self
    {
        if ($this->storageContainer->hasDataMapper() && !$this->hasSimpleResponse() && $this->query->hasCustomQuery()) {
            $dataMapper = $this->storageContainer->getDataMapper();

            $rawData = $dataMapper->simpleQuery($this->query->getCustomQuery());

            $this->response = $rawData ? (new RepositoryResponseFactory())->createSimple($rawData) : null;
        }

        return $this;
    }

    private function saveIdentityMap($data): self
    {
        if ($this->storageContainer->hasIdentityMap()) {
            $this
                ->storageContainer
                ->getIdentityMap()
                ->set($this->query->getIdentityMapKey(), $data);
        }
        return $this;
    }

    private function saveRussianDoll($data): self
    {
        if ($this->storageContainer->hasRussianDoll()) {
            $this->storageContainer
                ->getRussianDoll()
                ->setKey($this->query->getRussianDollKey())
                ->write($data);
        }
        return $this;
    }

    private function hasNonEmptyData($data): bool
    {
        return !empty($data) && is_array($data);
    }
}
