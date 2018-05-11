<?php

use G4\DataRepository\WriteRepository;
use G4\DataRepository\MapperCollection;
use G4\DataMapper\Common\MappingInterface;

class WriteRepositoryTest extends \PHPUnit_Framework_TestCase
{

    public function testDelete()
    {
        $command = $this->getCommandMock();
        $command->expects($this->exactly(1))->method('isDelete')->willReturn(true);
        $command->expects($this->exactly(1))->method('getIdentity')->willReturn($this->getIdentityMock());

        $storage = $this->getStorageMock();
        $storage->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($storage))->write($command));
    }

    public function testUpsert()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturn($mapperCollectionMock);
        $mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->getMappingMock());

        $command = $this->getCommandMock();
        $command->expects($this->exactly(1))->method('isUpsert')->willReturn(true);
        $command->expects($this->exactly(2))->method('getMap')->willReturn($mapperCollectionMock);

        $storage = $this->getStorageMock();
        $storage->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($storage))->write($command));
    }

    public function testInsert()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(1);
        $mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturn($mapperCollectionMock);
        $mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->getMappingMock());

        $command = $this->getCommandMock();
        $command->expects($this->exactly(1))->method('isInsert')->willReturn(true);
        $command->expects($this->exactly(2))->method('getMap')->willReturn($mapperCollectionMock);

        $storage = $this->getStorageMock();
        $storage->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($storage))->write($command));
    }

    public function testInsertBulk()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(3);

        $command = $this->getCommandMock();
        $command->expects($this->exactly(1))->method('isInsert')->willReturn(true);
        $command->expects($this->exactly(2))->method('getMap')->willReturn($mapperCollectionMock);

        $storage = $this->getStorageMock();
        $storage->expects($this->exactly(1))->method('getDataMapperBulk')->willReturn($this->getDataMapperBulkMock());
        $storage->expects($this->exactly(0))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($storage))->write($command));
    }

    public function testCustomCommand()
    {
        $command = $this->getCommandMock();
        $command->expects($this->exactly(1))->method('isCustomCommand')->willReturn(true);

        $storage = $this->getStorageMock();
        $storage->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());
        $storage->expects($this->exactly(0))->method('getDataMapperBulk')->willReturn($this->getDataMapperBulkMock());

        $this->assertEquals(null, (new WriteRepository($storage))->write($command));
    }

    public function testUpdate()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturn($mapperCollectionMock);
        $mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->getMappingMock());

        $command = $this->getCommandMock();
        $command->expects($this->exactly(1))->method('isUpdate')->willReturn(true);
        $command->expects($this->exactly(1))->method('getMap')->willReturn($mapperCollectionMock);
        $command->expects($this->exactly(1))->method('getIdentity')->willReturn($this->getIdentityMock());

        $storage = $this->getStorageMock();
        $storage->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($storage))->write($command));
    }

    public function testUpsertBulk()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(2);

        $command = $this->getCommandMock();
        $command->expects($this->exactly(1))->method('isUpsert')->willReturn(true);
        $command->expects($this->exactly(2))->method('getMap')->willReturn($mapperCollectionMock);

        $storage = $this->getStorageMock();
        $storage->expects($this->exactly(1))->method('getDataMapperBulk')->willReturn($this->getDataMapperBulkMock());

        $this->assertEquals(null, (new WriteRepository($storage))->write($command));
    }

    private function getStorageMock()
    {
        $storage = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storage->method('hasRussianDoll')->willReturn(true);
        $storage->method('hasDataMapper')->willReturn(true);
        $storage->method('hasIdentityMap')->willReturn(true);
        $storage->method('hasDatasetName')->willReturn(true);
        $storage->method('getRussianDoll')->willReturn($this->getRussianDollMock());
        $storage->method('getIdentityMap')->willReturn($this->getIdentityMapMock());

        return $storage;
    }

    private function getCommandMock()
    {
        $command =  $this->getMockBuilder(\G4\DataRepository\RepositoryCommand::class)
            ->disableOriginalConstructor()
            ->getMock();
        $command->method('getRussianDollKey')->willReturn($this->getRussianDollKeyMock());
        $command->method('getIdentityMapKey')->willReturn('key');
        return $command;
    }

    private function getIdentityMapMock()
    {
        $mock = $this->getMockBuilder(\G4\IdentityMap\IdentityMap::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    private function getIdentityMock()
    {
        return $this->getMockBuilder(\G4\DataMapper\Common\IdentityInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getRussianDollMock()
    {
        $mock = $this->getMockBuilder(\G4\RussianDoll\RussianDoll::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('setKey')->willReturn($mock);
        return $mock;
    }

    private function getRussianDollKeyMock()
    {
        return $this->getMockBuilder(\G4\RussianDoll\Key::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getDataMapperMock()
    {
        return $this->getMockBuilder(\G4\DataMapper\Common\MapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getDataMapperBulkMock()
    {
        return $this->getMockBuilder(\G4\DataMapper\Common\Bulk::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMapperCollectionMock()
    {
        return $this->getMockBuilder(MapperCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMappingMock()
    {
        return $this->getMockBuilder(MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}