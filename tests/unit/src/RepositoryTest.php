<?php

use G4\DataRepository\Repository;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{


    public function testInstance()
    {
        $storageContainerMock = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $repository = new Repository($storageContainerMock);
        $this->assertInstanceOf(Repository::class, $repository);
    }
}