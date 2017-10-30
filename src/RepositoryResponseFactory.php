<?php

namespace G4\DataRepository;

use G4\ValueObject\Dictionary;

class RepositoryResponseFactory
{

    public function create(\G4\DataMapper\Common\RawData $rawData)
    {
        $response = new DataRepositoryResponse($rawData->getAll());
        $response
            ->setCount($rawData->count())
            ->setTotal($rawData->getTotal());
        return $response;
    }

    public function createFromArray(Dictionary $data)
    {
        $response = new DataRepositoryResponse($data->get(RepositoryConstants::DATA));
        $response
            ->setCount($data->get(RepositoryConstants::COUNT))
            ->setTotal($data->get(RepositoryConstants::TOTAL));
        return $response;
    }
}
