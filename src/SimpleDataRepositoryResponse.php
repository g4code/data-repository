<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingResponseAllDataException;
use G4\DataRepository\Exception\MissingResponseOneDataException;

class SimpleDataRepositoryResponse
{
    private $data;
    private $count;

    public function __construct(array $currentItems, $count)
    {
        $this->data     = $currentItems;
        $this->count    = $count;
    }

    public function count()
    {
        return $this->count;
    }

    public function getAll()
    {
        if (!$this->hasData()) {
            throw new MissingResponseAllDataException();
        }
        return $this->data;
    }

    public function getOne()
    {
        if (!$this->hasData()) {
            throw new MissingResponseOneDataException();
        }
        return current($this->data);
    }

    public function hasData()
    {
        return count($this->data) !== 0;
    }
}
