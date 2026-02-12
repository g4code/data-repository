<?php

namespace G4\DataRepository;

use G4\DataMapper\Common\RawData;
use G4\DataMapper\Common\SimpleRawData;
use G4\ValueObject\Dictionary;

class RepositoryResponseFactory
{
    public function createSimple(SimpleRawData $rawData): SimpleDataRepositoryResponse
    {
        return new SimpleDataRepositoryResponse(
            $rawData->getAll(),
            $rawData->count()
        );
    }

    public function create(RawData $rawData): DataRepositoryResponse
    {
        return new DataRepositoryResponse(
            $rawData->getAll(),
            $rawData->count(),
            $rawData->getTotal()
        );
    }

    public function createFromArray(Dictionary $data): DataRepositoryResponse
    {
        return new DataRepositoryResponse(
            $data->get(RepositoryConstants::DATA),
            $data->get(RepositoryConstants::COUNT),
            $data->get(RepositoryConstants::TOTAL)
        );
    }

    public function createEmptyResponse(): DataRepositoryResponse
    {
        return new DataRepositoryResponse([], 0, 0);
    }

    public function createSimpleEmptyResponse(): SimpleDataRepositoryResponse
    {
        return new SimpleDataRepositoryResponse([], 0);
    }
}
