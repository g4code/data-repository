<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingResponseAllDataException;
use G4\DataRepository\Exception\MissingResponseOneDataException;

class DataRepositoryResponse
{
    public function __construct(private readonly array $data, private $count, private $total)
    {
    }

    public function count(): int
    {
        return $this->count;
    }

    public function getAll(): array
    {
        if (!$this->hasData() && $this->getTotal() === 0) {
            throw new MissingResponseAllDataException();
        }
        return $this->data;
    }

    public function getOne(): mixed
    {
        if (!$this->hasData()) {
            throw new MissingResponseOneDataException();
        }
        return current($this->data);
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function hasData(): bool
    {
        return count($this->data) !== 0;
    }
}
