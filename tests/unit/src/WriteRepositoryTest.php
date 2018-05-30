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


    public function testDelete()
    {
        $this->repositoryCommandMock->expects($this->exactly(1))->method('isDelete')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(1))->method('getIdentity')->willReturn($this->getIdentityMock());

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testUpsert()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturn($mapperCollectionMock);
        $mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->getMappingMock());

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isUpsert')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(2))->method('getMap')->willReturn($mapperCollectionMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testInsert()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(1);
        $mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturn($mapperCollectionMock);
        $mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->getMappingMock());

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isInsert')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(2))->method('getMap')->willReturn($mapperCollectionMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testInsertBulk()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(3);

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isInsert')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(2))->method('getMap')->willReturn($mapperCollectionMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapperBulk')->willReturn($this->getDataMapperBulkMock());
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testCustomCommand()
    {
        $this->repositoryCommandMock->expects($this->exactly(1))->method('isCustomCommand')->willReturn(true);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapperBulk')->willReturn($this->getDataMapperBulkMock());

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testUpdate()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('rewind')->willReturn($mapperCollectionMock);
        $mapperCollectionMock->expects($this->exactly(1))->method('current')->willReturn($this->getMappingMock());

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isUpdate')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(1))->method('getMap')->willReturn($mapperCollectionMock);
        $this->repositoryCommandMock->expects($this->exactly(1))->method('getIdentity')->willReturn($this->getIdentityMock());

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    public function testUpsertBulk()
    {
        $mapperCollectionMock = $this->getMapperCollectionMock();
        $mapperCollectionMock->expects($this->exactly(1))->method('count')->willReturn(2);

        $this->repositoryCommandMock->expects($this->exactly(1))->method('isUpsert')->willReturn(true);
        $this->repositoryCommandMock->expects($this->exactly(2))->method('getMap')->willReturn($mapperCollectionMock);

        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapperBulk')->willReturn($this->getDataMapperBulkMock());

        $this->assertEquals(null, (new WriteRepository($this->storageContainerMock))->write($this->repositoryCommandMock));
    }

    protected function setUp()
    {
        $this->storageContainerMock = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storageContainerMock->method('hasRussianDoll')->willReturn(true);
        $this->storageContainerMock->method('hasDataMapper')->willReturn(true);
        $this->storageContainerMock->method('hasIdentityMap')->willReturn(true);
        $this->storageContainerMock->method('hasDatasetName')->willReturn(true);
        $this->storageContainerMock->method('getRussianDoll')->willReturn($this->getRussianDollMock());
        $this->storageContainerMock->method('getIdentityMap')->willReturn($this->getIdentityMapMock());

        $this->repositoryCommandMock = $this->getMockBuilder(\G4\DataRepository\RepositoryCommand::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryCommandMock->method('getRussianDollKey')->willReturn($this->getRussianDollKeyMock());
        $this->repositoryCommandMock->method('getIdentityMapKey')->willReturn('key');
    }

    protected function tearDown()
    {
        $this->storageContainerMock     = null;
        $this->repositoryCommandMock    = null;
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