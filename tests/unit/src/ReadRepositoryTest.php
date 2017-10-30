<?php

use G4\DataRepository\DataRepositoryResponse;
use G4\DataRepository\ReadRepository;
use G4\DataMapper\Common\MappingInterface;

class ReadRepositoryTest extends \PHPUnit_Framework_TestCase
{

    public function testReadException()
    {
        $this->expectException(\G4\DataRepository\Exception\NotFoundException::class);

        $query = $this->getQueryMock();
        $query->method('getIdentity')->willReturn($this->getIdentityMock());

        $storage = $this->getStorageMock();
        $storage->method('getDataMapper')->willReturn($this->getDataMapperMock());
        $response = (new ReadRepository($storage))->read($query);

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

    private function getQueryMock()
    {
        $command =  $this->getMockBuilder(\G4\DataRepository\RepositoryQuery::class)
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
        $mock =  $this->getMockBuilder(\G4\DataMapper\Common\MapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('find')->willReturn($this->getRawDataMock());
        return $mock;
    }

    private function getMapMock()
    {
        return $this->getMockBuilder(MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getRawDataMock()
    {
        $mock =  $this->getMockBuilder(\G4\DataMapper\Common\RawData::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getAll')->willReturn([]);
        $mock->method('count')->willReturn(0);
        $mock->method('getTotal')->willReturn(0);
        return $mock;
    }
}