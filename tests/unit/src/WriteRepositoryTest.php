<?php

use G4\DataRepository\DataRepositoryResponse;
use G4\DataRepository\WriteRepository;
use G4\DataMapper\Common\MappingInterface;

class WriteRepositoryTest extends \PHPUnit_Framework_TestCase
{

    public function testDelete()
    {
        $command = $this->getCommandMock();
        $command->method('isDelete')->willReturn(true);
        $command->method('getIdentity')->willReturn($this->getIdentityMock());

        $storage = $this->getStorageMock();
        $storage->method('getDataMapper')->willReturn($this->getDataMapperMock());

        (new WriteRepository($storage))->write($command);
    }

    public function testUpsert()
    {
        $command = $this->getCommandMock();
        $command->method('isUpsert')->willReturn(true);
        $command->method('getMap')->willReturn($this->getMapMock());

        $storage = $this->getStorageMock();
        $storage->method('getDataMapper')->willReturn($this->getDataMapperMock());

        (new WriteRepository($storage))->write($command);
    }

    public function testInsert()
    {
        $command = $this->getCommandMock();
        $command->method('isInsert')->willReturn(true);
        $command->method('getMap')->willReturn($this->getMapMock());

        $storage = $this->getStorageMock();
        $storage->method('getDataMapper')->willReturn($this->getDataMapperMock());

        (new WriteRepository($storage))->write($command);
    }

    public function testCustomCommand()
    {
        $command = $this->getCommandMock();
        $command->method('isCustomCommand')->willReturn(true);

        $storage = $this->getStorageMock();
        $storage->method('getDataMapper')->willReturn($this->getDataMapperMock());
        (new WriteRepository($storage))->write($command);
    }

    public function testUpdate()
    {
        $command = $this->getCommandMock();
        $command->method('isUpdate')->willReturn(true);
        $command->method('getMap')->willReturn($this->getMapMock());
        $command->method('getIdentity')->willReturn($this->getIdentityMock());

        $storage = $this->getStorageMock();
        $storage->method('getDataMapper')->willReturn($this->getDataMapperMock());

        (new WriteRepository($storage))->write($command);
    }

    private function getStorageMock()
    {
        $storage = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storage->method('hasRussianDoll')->willReturn(true);
        $storage->method('hasDataMapper')->willReturn(true);
        $storage->method('hasIdentityMap')->willReturn(true);
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
        return  $this->getMockBuilder(\G4\DataMapper\Common\IdentityInterface::class)
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

    private function getMapMock()
    {
        return $this->getMockBuilder(MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}