<?php

use G4\DataRepository\RepositoryFactory;
use G4\DataRepository\Repository;
use G4\DataRepository\StorageContainer;
use G4\DataMapper\Common\MapperInterface;

class RepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{


    public function testCreate()
    {
        $storageContainerStub = $this->getMockBuilder(StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryFactoryMock = $this->getMockBuilder(RepositoryFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['makeStorageContainer'])
            ->getMock();

        $repositoryFactoryMock
            ->expects($this->once())
            ->method('makeStorageContainer')
            ->willReturn($storageContainerStub);

        $this->assertInstanceOf(Repository::class, $repositoryFactoryMock->create());
    }

    public function testMakeStorageContainer()
    {
        $mapperMock = $this->getMockBuilder(MapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryFactory = new RepositoryFactory($mapperMock);
        $this->assertInstanceOf(StorageContainer::class, $repositoryFactory->makeStorageContainer());
    }
}