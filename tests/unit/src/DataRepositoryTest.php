<?php

use G4\DataRepository\DataRepository;

class DataRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $storageContainerMock;

    public function setUp()
    {
        $this->storageContainerMock = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testInstance()
    {
        $repository = new DataRepository($this->storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository);
    }
}