<?php

use G4\RussianDoll\RussianDoll;
use G4\IdentityMap\IdentityMap;
use G4\DataMapper\Common\MapperInterface;
use G4\DataRepository\DataRepository;
use G4\DataRepository\StorageContainer;
use G4\DataRepository\Exception\MissingStorageException;
use G4\DataRepository\Exception\NotValidStorageException;

class StorageContainerTest extends PHPUnit_Framework_TestCase
{


    public function testMissingStorageException()
    {
        $this->expectException(MissingStorageException::class);
        new StorageContainer([]);
    }

    public function testNotValidStorageException()
    {
        $stub = $this->getMockBuilder(DataRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(NotValidStorageException::class);
        new StorageContainer([$stub]);
    }

    public function testGetters()
    {
        $identityMapStub = $this->getMockBuilder(IdentityMap::class)
            ->disableOriginalConstructor()
            ->getMock();

        $russianDollStub = $this->getMockBuilder(RussianDoll::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mapperStub = $this->getMockBuilder(MapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storageContainer = new StorageContainer([$russianDollStub, $identityMapStub, $mapperStub]);

        $this->assertEquals($identityMapStub, $storageContainer->getIdentityMap());
        $this->assertEquals($russianDollStub, $storageContainer->getRussianDoll());
        $this->assertEquals($mapperStub, $storageContainer->getDataMapper());

        $this->assertTrue($storageContainer->hasIdentityMap());
        $this->assertTrue($storageContainer->hasRussianDoll());
        $this->assertTrue($storageContainer->hasDataMapper());
    }
}