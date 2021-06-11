<?php

namespace G4\DataRepository;

use G4\ValueObject\Dictionary;

class RepositoryResponseFactory
{
    public function createSimple(\G4\DataMapper\Common\SimpleRawData $rawData)
    {
        return new SimpleDataRepositoryResponse(
            $rawData->getAll(),
            $rawData->count()
        );
    }

    public function create(\G4\DataMapper\Common\RawData $rawData)
    {
        return new DataRepositoryResponse(
            $rawData->getAll(),
            $rawData->count(),
            $rawData->getTotal()
        );
    }

    public function createFromArray(Dictionary $data)
    {
        return new DataRepositoryResponse(
            $data->get(RepositoryConstants::DATA),
            $data->get(RepositoryConstants::COUNT),
            $data->get(RepositoryConstants::TOTAL)
        );
    }

    public function createEmptyResponse()
    {
        return new DataRepositoryResponse([], 0, 0);
    }

    public function createSimpleEmptyResponse()
    {
        return new SimpleDataRepositoryResponse([], 0);
    }
}
