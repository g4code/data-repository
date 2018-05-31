<?php

use G4\DataRepository\WriteRepository;
use G4\DataRepository\MapperCollection;
use G4\DataMapper\Common\MappingInterface;

class WriteRepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $storageContainerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryCommandMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $identityMapMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $identityInterfaceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $russianDollMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $russianDollKeyMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mapperInterfaceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mapperBulkMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mapperCollectionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mappingInterfaceMock;


    public function testDelete()
    {
        $this->repositoryCommandMock->expects($this->exactly(1))->method('isDelete')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(1))->method('getIdentity')->willReturn($this->identityInterfaceMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->mapperInterfaceMock);

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testUpsert()
    {
        $this->mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturnSelf();
        $this->mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->mappingInterfaceMock);

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isUpsert')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(2))->method('getMap')->willReturn($this->mapperCollectionMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->mapperInterfaceMock);

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testInsert()
    {
        $this->mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(1);
        $this->mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturnSelf();
        $this->mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->mappingInterfaceMock);

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isInsert')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(2))->method('getMap')->willReturn($this->mapperCollectionMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->mapperInterfaceMock);

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testInsertBulk()
    {
        $this->mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(3);

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isInsert')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(2))->method('getMap')->willReturn($this->mapperCollectionMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapperBulk')->willReturn($this->mapperBulkMock);
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapper')->willReturn($this->mapperInterfaceMock);

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testCustomCommand()
    {
        $this->repositoryCommandMock->expects($this->exactly(1))->method('isCustomCommand')->willReturn(true);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->mapperInterfaceMock);
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapperBulk')->willReturn($this->mapperBulkMock);

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testUpdate()
    {
        $this->mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturnSelf();
        $this->mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->mappingInterfaceMock);

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isUpdate')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(1))->method('getMap')->willReturn($this->mapperCollectionMock);
        $this->repositoryCommandMock->expects($this->exactly(1))->method('getIdentity')->willReturn($this->identityInterfaceMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->mapperInterfaceMock);

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testUpsertBulk()
    {
        $this->mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(2);

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isUpsert')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(2))->method('getMap')->willReturn($this->mapperCollectionMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapperBulk')->willReturn($this->mapperBulkMock);

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    protected function setUp()
    {
        $this->identityMapMock = $this->getMockBuilder(\G4\IdentityMap\IdentityMap::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->russianDollMock = $this->getMockBuilder(\G4\RussianDoll\RussianDoll::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->russianDollMock->method('setKey')->willReturnSelf();

        $this->storageContainerMock = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storageContainerMock->method('hasRussianDoll')->willReturn(true);
        $this->storageContainerMock->method('hasDataMapper')->willReturn(true);
        $this->storageContainerMock->method('hasIdentityMap')->willReturn(true);
        $this->storageContainerMock->method('hasDatasetName')->willReturn(true);
        $this->storageContainerMock->method('getRussianDoll')->willReturn($this->russianDollMock);
        $this->storageContainerMock->method('getIdentityMap')->willReturn($this->identityMapMock);

        $this->russianDollKeyMock = $this->getMockBuilder(\G4\RussianDoll\Key::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryCommandMock = $this->getMockBuilder(\G4\DataRepository\RepositoryCommand::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryCommandMock->method('getRussianDollKey')->willReturn($this->russianDollKeyMock);
        $this->repositoryCommandMock->method('getIdentityMapKey')->willReturn('key');

        $this->identityInterfaceMock = $this->getMockBuilder(\G4\DataMapper\Common\IdentityInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapperInterfaceMock = $this->getMockBuilder(\G4\DataMapper\Common\MapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapperBulkMock = $this->getMockBuilder(\G4\DataMapper\Common\Bulk::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapperCollectionMock = $this->getMockBuilder(MapperCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mappingInterfaceMock = $this->getMockBuilder(MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        $this->storageContainerMock     = null;
        $this->repositoryCommandMock    = null;
        $this->identityMapMock          = null;
        $this->russianDollMock          = null;
        $this->russianDollKeyMock       = null;
        $this->mapperInterfaceMock      = null;
        $this->mapperBulkMock           = null;
        $this->mapperCollectionMock     = null;
        $this->mappingInterfaceMock     = null;
    }
}
