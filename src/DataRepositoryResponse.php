<?php

namespace G4\DataRepository;

use G4\DataRepository\Exception\MissingResponseAllDataException;
use G4\DataRepository\Exception\MissingResponseCountException;
use G4\DataRepository\Exception\MissingResponseOneDataException;
use G4\DataRepository\Exception\MissingResponseTotalException;

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
        if(!$this->hasData()){
            throw new MissingResponseCountException();
        }
        return $this->count;
    }

    public function getAll()
    {
        if(!$this->hasData()){
            throw new MissingResponseAllDataException();
        }
        return $this->data;
    }

    public function getOne()
    {
        if(!$this->hasData()){
            throw new MissingResponseOneDataException();
        }
        return current($this->data);
    }

    public function getTotal()
    {
        if(!$this->hasData()){
            throw new MissingResponseTotalException();
        }
        return $this->total;
    }

    public function hasData()
    {
        return count($this->data) !== 0;
    }
}
