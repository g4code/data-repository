<?php

use G4\DataRepository\DataRepositoryFactory;
use G4\DataRepository\DataRepository;
use G4\DataRepository\StorageContainer;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Builder;

class RepositoryFactoryTest extends \PHPUnit\Framework\TestCase
{

    private $mapperBuilderStub;

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
        $this->mapperBuilderStub = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapperBuilderStub
            ->method('collectionName')
            ->willReturn($this->mapperBuilderStub);

        $repositoryFactory = new DataRepositoryFactory($this->mapperBuilderStub);

        $this->assertInstanceOf(StorageContainer::class, $repositoryFactory->makeStorageContainer());
    }
}