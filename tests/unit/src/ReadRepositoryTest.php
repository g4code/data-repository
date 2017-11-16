<?php

use G4\DataRepository\DataRepositoryResponse;
use G4\DataRepository\ReadRepository;
use G4\DataMapper\Common\MappingInterface;

class ReadRepositoryTest extends \PHPUnit_Framework_TestCase
{

    public function testReadEmpty()
    {
        $query = $this->getQueryMock();
        $query->method('getIdentity')->willReturn($this->getIdentityMock());

        $storage = $this->getStorageMock();
        $mapperMock =  $this->getDataMapperMock();
        $mapperMock->method('find')->willReturn($this->getEmptyRawDataMock());
        $storage->method('getDataMapper')->willReturn($mapperMock);
        $response = (new ReadRepository($storage))->read($query);
        $this->assertFalse($response->hasData());
    }

    public function testRead()
    {
        $query = $this->getQueryMock();
        $query->method('getIdentity')->willReturn($this->getIdentityMock());

        $mapperMock =  $this->getDataMapperMock();
        $mapperMock->method('find')->willReturn($this->getRawDataMock());

        $storage = $this->getStorageMock();
        $storage->method('getDataMapper')->willReturn($mapperMock);

        $response = (new ReadRepository($storage))->read($query);
        $this->assertInstanceOf(DataRepositoryResponse::class, $response);
    }

    // TODO - tmp - write better
    public function testReadFromIdentity()
    {
        $identityMapMock = $this->getIdentityMock();

        $query = $this->getQueryMock();
        $query->method('getIdentity')->willReturn($identityMapMock);

        $mapperMock =  $this->getDataMapperMock();
        $mapperMock->method('find')->willReturn($this->getRawDataMock());

        $storage = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $identity = $this->getIdentityMapMock();
        $identity->method('get')->willReturn($this->getData());

        $storage->method('hasRussianDoll')->willReturn(true);
        $storage->method('hasDataMapper')->willReturn(true);
        $storage->method('hasIdentityMap')->willReturn(true);
        $storage->method('getRussianDoll')->willReturn($this->getRussianDollMock());
        $storage->method('getIdentityMap')->willReturn($identity);
        $storage->method('getDataMapper')->willReturn($mapperMock);

        $response = (new ReadRepository($storage))->read($query);
        $this->assertInstanceOf(DataRepositoryResponse::class, $response);
    }


    // TODO - tmp - write better
    public function testReadFromRussiandDoll()
    {
        $identityMapMock = $this->getIdentityMock();

        $query = $this->getQueryMock();
        $query->method('getIdentity')->willReturn($identityMapMock);

        $mapperMock =  $this->getDataMapperMock();

        $storage = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $russianDollMock = $this->getRussianDollMock();
        $russianDollMock->method('fetch')->willReturn($this->getData());

        $storage->method('hasRussianDoll')->willReturn(true);
        $storage->method('hasDataMapper')->willReturn(true);
        $storage->method('hasIdentityMap')->willReturn(true);
        $storage->method('getRussianDoll')->willReturn($russianDollMock);
        $storage->method('getIdentityMap')->willReturn($this->getIdentityMapMock());
        $storage->method('getDataMapper')->willReturn($mapperMock);

        $response = (new ReadRepository($storage))->read($query);
        $this->assertInstanceOf(DataRepositoryResponse::class, $response);
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
        return $mock;
    }

    private function getMapMock()
    {
        return $this->getMockBuilder(MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getEmptyRawDataMock()
    {
        $mock =  $this->getMockBuilder(\G4\DataMapper\Common\RawData::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getAll')->willReturn([]);
        $mock->method('count')->willReturn(0);
        $mock->method('getTotal')->willReturn(0);
        return $mock;
    }

    private function getRawDataMock()
    {
        $mock =  $this->getMockBuilder(\G4\DataMapper\Common\RawData::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mock->method('getAll')->willReturn([
            [
                'user_id'   => 1,
                'usernmae'  => 'blabla'
            ]
        ]);
        $mock->method('count')->willReturn(1);
        $mock->method('getTotal')->willReturn(1);
        return $mock;
    }

    private function getData()
    {
        return [
            'data'  => [
                [
                    'user_id'   => 1,
                    'usernmae'  => 'blabla'
                ]
            ],
            'total' => 1,
            'count' => 1
        ];
    }
}