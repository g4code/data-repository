<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingResponseAllDataException;
use G4\DataRepository\Exception\MissingResponseOneDataException;

class SimpleDataRepositoryResponse
{
    public function __construct(private readonly array $data, private $count)
    {
    }

    public function count(): int
    {
        return $this->count;
    }

    public function getAll(): array
    {
        if (!$this->hasData()) {
            throw new MissingResponseAllDataException();
        }
        return $this->data;
    }

    /**
     * @throws MissingResponseOneDataException
     */
    public function getOne(): mixed
    {
        if (!$this->hasData()) {
            throw new MissingResponseOneDataException();
        }
        return current($this->data);
    }

    public function hasData(): bool
    {
        return count($this->data) !== 0;
    }
}
