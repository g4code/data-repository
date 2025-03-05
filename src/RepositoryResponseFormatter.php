<?php

namespace G4\DataRepository;

class RepositoryResponseFormatter
{
    public function __construct(private readonly DataRepositoryResponse $repositoryResponse)
    {
    }

    public function format(): array
    {
        return [
            RepositoryConstants::TOTAL => $this->repositoryResponse->getTotal(),
            RepositoryConstants::COUNT => $this->repositoryResponse->count(),
            RepositoryConstants::DATA => $this->repositoryResponse->getAll(),
        ];
    }
}
