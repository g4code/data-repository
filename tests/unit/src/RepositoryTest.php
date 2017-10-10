<?php

use G4\DataRepository\DataRepository;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{


    public function testInstance()
    {
        $storageContainerMock = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repository = new DataRepository($storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository);
    }
}