<?php

namespace G4\DataRepository;

class MapperCollection implements \Iterator, \Countable
{
    /**
     * @var int
     */
    private $total;

    /**
     * @var array
     */
    private $keyMap;

    /**
     * @var array
     */
    private $rawData;

    /**
     * @var int
     */
    private $pointer;

    /**
     * MapperCollection constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->keyMap  = array_keys($data);
        $this->rawData = $data;
        $this->pointer = 0;
    }

    /**
     * Count elements of an object
     * @return int The custom count as an integer.
     */
    public function count()
    {
        if ($this->total === null) {
            $this->total = count($this->rawData);
        }
        return $this->total;
    }

    /**
     * @return mixed|null
     */
    public function current()
    {
        if ($this->pointer >= $this->count()) {
            return null;
        }

        if ($this->hasCurrentRawData()) {
            return $this->currentRawData();
        }
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        if ($this->pointer < $this->count()) {
            $this->pointer++;
        }
    }

    /**
     * Return the key of the current element
     * @return int
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Checks if current position is valid
     * @return bool
     */
    public function valid()
    {
        return $this->current() !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @return $this
     */
    public function rewind()
    {
        $this->pointer = 0;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasData()
    {
        return $this->count() > 0;
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @return bool
     */
    private function hasCurrentRawData()
    {
        return isset($this->keyMap[$this->pointer]) && isset($this->rawData[$this->keyMap[$this->pointer]]);
    }

    /**
     * @return array
     */
    private function currentRawData()
    {
        return $this->rawData[$this->keyMap[$this->pointer]];
    }
}
