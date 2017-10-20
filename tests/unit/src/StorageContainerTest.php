<?php

use G4\RussianDoll\RussianDoll;
use G4\IdentityMap\IdentityMap;
use G4\DataMapper\Builder;
use G4\DataMapper\Common\MapperInterface;
use G4\DataRepository\DataRepository;
use G4\DataRepository\StorageContainer;
use G4\DataRepository\Exception\MissingStorageException;
use G4\DataRepository\Exception\NotValidStorageException;

class StorageContainerTest extends PHPUnit_Framework_TestCase
{

    private $identityMapStub;
    private $russianDollStub;
    private $mapperStub;
    private $mapperBuilderStub;

    public function setUp()
    {
        $this->identityMapStub = $this->getMockBuilder(IdentityMap::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->russianDollStub = $this->getMockBuilder(RussianDoll::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapperStub = $this->getMockBuilder(MapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapperBuilderStub = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapperBuilderStub
            ->method('collectionName')
            ->willReturn($this->mapperBuilderStub);

        $this->mapperBuilderStub
            ->method('buildMapper')
            ->willReturn($this->mapperStub);


    }

    public function tearDown()
    {
        $this->identityMapStub      = null;
        $this->russianDollStub      = null;
        $this->mapperStub           = null;
        $this->mapperBuilderStub    = null;
    }

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
        $storageContainer = new StorageContainer([$this->russianDollStub, $this->identityMapStub, $this->mapperBuilderStub]);
        $storageContainer->makeDataMapper('__table_name__');

        $this->assertEquals($this->identityMapStub, $storageContainer->getIdentityMap());
        $this->assertEquals($this->russianDollStub, $storageContainer->getRussianDoll());
        $this->assertEquals($this->mapperStub, $storageContainer->getDataMapper());

        $this->assertTrue($storageContainer->hasIdentityMap());
        $this->assertTrue($storageContainer->hasRussianDoll());
        $this->assertTrue($storageContainer->hasDataMapper());
    }
}