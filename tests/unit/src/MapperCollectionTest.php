<?php

use G4\DataRepository\MapperCollection;

class MapperCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MapperCollection
     */
    private $collection;

    /**
     * @var array
     */
    private $data;

    protected function setUp()
    {
        $this->data = [
            0 => 'first_mapping',
            2 => 'second_mapping',
        ];
        $this->collection = new MapperCollection($this->data);
    }

    protected function tearDown()
    {
        $this->data       = null;
        $this->collection = null;
    }

    public function testCount()
    {
        $this->assertEquals(2, $this->collection->count());
    }

    public function testCurrent()
    {
        $this->assertEquals($this->data[0], $this->collection->current());
        $this->collection->next();
        $this->assertEquals($this->data[2], $this->collection->current());
    }

    public function testNext()
    {
        $this->collection->next();
        $this->assertNotEquals($this->data[0], $this->collection->current());
        $this->assertEquals($this->data[2], $this->collection->current());
        $this->collection->next();
        $this->assertNull($this->collection->current());
    }

    public function testKey()
    {
        $this->assertEquals(0, $this->collection->key());

        $this->collection->next();
        $this->assertEquals(1, $this->collection->key());

        $this->collection->rewind();
        $this->assertEquals(0, $this->collection->key());
    }

    public function testValid()
    {
        $this->assertTrue($this->collection->valid());
        $this->collection->next();
        $this->assertTrue($this->collection->valid());
        $this->collection->next();
        $this->assertFalse($this->collection->valid());
    }

    public function testRewind()
    {
        $this->collection->rewind();
        $this->assertEquals(0, $this->collection->key());
    }

    public function testHasData()
    {
        $this->assertTrue($this->collection->hasData());
    }

    public function testGetData()
    {
        $this->assertEquals($this->data, $this->collection->getRawData());
    }
}
