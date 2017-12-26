<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingResponseAllDataException;
use G4\DataRepository\Exception\MissingResponseOneDataException;

class DataRepositoryResponse
{
    private $data;
    private $count;
    private $total;

    public function __construct(array $currentItems, $count, $total)
    {
        $this->data     = $currentItems;
        $this->count    = $count;
        $this->total    = $total;
    }

    public function count()
    {
        return $this->count;
    }

    public function getAll()
    {
        if (!$this->hasData() && $this->getTotal() === 0) {
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

    public function getTotal()
    {
        return $this->total;
    }

    public function hasData()
    {
        return count($this->data) !== 0;
    }
}
