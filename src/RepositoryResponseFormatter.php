<?php

namespace G4\DataRepository;

class RepositoryResponseFormatter
{
    private $repositoryResponse;

    public function __construct(DataRepositoryResponse $repositoryResponse)
    {
        $this->repositoryResponse = $repositoryResponse;
    }

    public function format()
    {
        return [
            RepositoryConstants::TOTAL     => $this->repositoryResponse->getTotal(),
            RepositoryConstants::COUNT     => $this->repositoryResponse->count(),
            RepositoryConstants::DATA      => $this->repositoryResponse->getAll(),
        ];
    }
}
