<?php

use G4\RussianDoll\RussianDoll;
use G4\IdentityMap\IdentityMap;
use G4\DataMapper\Builder;
use G4\DataMapper\Common\Bulk;
use G4\DataMapper\Common\MapperInterface;
use G4\DataRepository\DataRepository;
use G4\DataRepository\StorageContainer;
use G4\DataRepository\Exception\MissingStorageException;
use G4\DataRepository\Exception\NotValidStorageException;
use G4\DataRepository\Exception\MissingDatasetNameValueException;

class StorageContainerTest extends \PHPUnit\Framework\TestCase
{

    private $identityMapStub;
    private $russianDollStub;
    private $mapperStub;
    private $mapperStubBulk;
    private $mapperBuilderStub;

    public function setUp(): void
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

        $this->mapperStubBulk = $this->getMockBuilder(Bulk::class)
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

        $this->mapperBuilderStub
            ->method('buildBulk')
            ->willReturn($this->mapperStubBulk);
    }

    public function tearDown(): void
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

    public function testMissingDatasetNameValueException()
    {
        $this->expectException(MissingDatasetNameValueException::class);

        $storageContainer = new StorageContainer([$this->mapperBuilderStub]);
        $storageContainer->getDataMapper();
    }

    public function testMissingDatasetNameValueExceptionBulk()
    {
        $this->expectException(MissingDatasetNameValueException::class);

        $storageContainer = new StorageContainer([$this->mapperBuilderStub]);
        $storageContainer->getDataMapperBulk();
    }

    public function testGetters()
    {
        $storageContainer = new StorageContainer([$this->russianDollStub, $this->identityMapStub, $this->mapperBuilderStub]);
        $storageContainer->setDatasetName('__table_name__');
        $storageContainer->getDataMapper();

        $this->assertEquals($this->identityMapStub, $storageContainer->getIdentityMap());
        $this->assertEquals($this->russianDollStub, $storageContainer->getRussianDoll());
        $this->assertEquals($this->mapperStub, $storageContainer->getDataMapper());

        $this->assertTrue($storageContainer->hasIdentityMap());
        $this->assertTrue($storageContainer->hasRussianDoll());
        $this->assertTrue($storageContainer->hasDataMapper());

        $storageContainer->getDataMapper();

        $this->assertEquals($this->mapperStubBulk, $storageContainer->getDataMapperBulk());
        $this->assertTrue($storageContainer->hasDataMapperBulk());
    }

    public function testHasDataMapperBuilder()
    {
        $storageContainer = new StorageContainer([$this->russianDollStub, $this->identityMapStub, $this->mapperBuilderStub]);
        $storageContainer->setDatasetName('__table_name__');
        $storageContainer->getDataMapper();

        $this->assertTrue($storageContainer->hasDataMapperBuilder());
    }

    public function testHasDatesetName()
    {
        $storageContainer = new StorageContainer([$this->russianDollStub, $this->identityMapStub, $this->mapperBuilderStub]);
        $this->assertFalse($storageContainer->hasDatasetName());

        $storageContainer->setDatasetName('__table_name__');
        $this->assertTrue($storageContainer->hasDatasetName());
    }
}