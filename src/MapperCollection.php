<?php

namespace G4\DataRepository;

class MapperCollection implements \Iterator, \Countable
{
    private ?int $total = null;

    private array $keyMap;

    private array $rawData;

    private int $pointer = 0;

    /**
     * MapperCollection constructor.
     */
    public function __construct(array $data)
    {
        $this->keyMap  = array_keys($data);
        $this->rawData = $data;
    }

    /**
     * Count elements of an object
     * @return int The custom count as an integer.
     */
    public function count(): int
    {
        if ($this->total === null) {
            $this->total = count($this->rawData);
        }
        return $this->total;
    }

    public function current(): mixed
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
    public function next(): void
    {
        if ($this->pointer < $this->count()) {
            $this->pointer++;
        }
    }

    /**
     * Return the key of the current element
     */
    public function key(): mixed
    {
        return $this->pointer;
    }

    /**
     * Checks if current position is valid
     * @return bool
     */
    public function valid(): bool
    {
        return $this->current() !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @return $this
     */
    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function hasData(): bool
    {
        return $this->count() > 0;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }

    private function hasCurrentRawData(): bool
    {
        return isset($this->keyMap[$this->pointer]) && isset($this->rawData[$this->keyMap[$this->pointer]]);
    }

    private function currentRawData():  mixed
    {
        return $this->rawData[$this->keyMap[$this->pointer]];
    }
}
