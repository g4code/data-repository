<?php

namespace G4\DataRepository;

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
        return $this->data;
    }

    public function getOne()
    {
        return $this->count() > 0
            ? current($this->data)
            : null; // TODO - throw exception
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function hasData()
    {
        return !empty($this->data);
    }
}
