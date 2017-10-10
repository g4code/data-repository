<?php

namespace G4\DataRepository;

class DataRepositoryResponse
{
    private $data;
    private $count;
    private $total;

    public function __construct(array $currentItems)
    {
        $this->data = $currentItems;
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

    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
}