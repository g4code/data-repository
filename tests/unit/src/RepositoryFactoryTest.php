<?php

use G4\DataRepository\DataRepositoryFactory;
use G4\DataRepository\DataRepository;
use G4\DataRepository\StorageContainer;
use G4\DataMapper\Common\MapperInterface;

class RepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{


    public function testCreate()
    {
        $storageContainerStub = $this->getMockBuilder(StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryFactoryMock = $this->getMockBuilder(DataRepositoryFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['makeStorageContainer'])
            ->getMock();

        $repositoryFactoryMock
            ->expects($this->once())
            ->method('makeStorageContainer')
            ->willReturn($storageContainerStub);

        $this->assertInstanceOf(DataRepository::class, $repositoryFactoryMock->create());
    }

    public function testMakeStorageContainer()
    {
        $mapperMock = $this->getMockBuilder(MapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoryFactory = new DataRepositoryFactory($mapperMock);
        $this->assertInstanceOf(StorageContainer::class, $repositoryFactory->makeStorageContainer());
    }
}