<?php

use G4\DataRepository\DataRepository;

class DataRepositoryTest extends \PHPUnit_Framework_TestCase
{
    const TABLE_NAME = '__TABLE_NAME__';

    private $storageContainerMock;

    public function setUp()
    {
        $this->storageContainerMock = $this->getMockBuilder(\G4\DataRepository\StorageContainer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testInstance()
    {
        $repository = new DataRepository($this->storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository);
    }

    public function testSetIdentity()
    {
        $identity = $this->getMockBuilder(\G4\DataMapper\Common\IdentityInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repository = new DataRepository($this->storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository->setIdentity($identity));
        $this->assertSame($identity, $repository->getIdentity());
    }

    public function testSetRussianDollKey()
    {
        $key = $this->getRussianDollKey();
        $repository = new DataRepository($this->storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository->setRussianDollKey($key));
        $this->assertSame($key, $repository->getRussianDollKey());
    }

    public function testSetRussianDollKeySetsIdentityMapKey()
    {
        $key = $this->getRussianDollKey();
        $repository = new DataRepository($this->storageContainerMock);
        $repository->setRussianDollKey($key);
        $this->assertNotNull($repository->getIdentityMapKey());
    }

    public function testSetIdentityMapKey()
    {
        $repository = new DataRepository($this->storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository->setIdentityMapKey(self::TABLE_NAME));
        $this->assertInternalType('string', $repository->getIdentityMapKey());
        $this->assertEquals(self::TABLE_NAME, $repository->getIdentityMapKey());
        $this->assertNotNull($repository->getIdentityMapKey());
    }

    public function testSetIdentityMapKeyCompound()
    {
        $keyParts = ['123', '345'];
        $repository = new DataRepository($this->storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository->setIdentityMapKey($keyParts[0], $keyParts[1]));
        $this->assertEquals(implode('|', $keyParts), $repository->getIdentityMapKey());
    }

    public function testSetDatasetName()
    {
        $repository = new DataRepository($this->storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository->setDatasetName(self::TABLE_NAME));
    }

    public function testSetMapping()
    {
        $repository = new DataRepository($this->storageContainerMock);
        $this->assertInstanceOf(DataRepository::class, $repository->setMapping($this->getMappingMock()));
        $this->assertInstanceOf(DataRepository::class, $repository->setMapping($this->getMappingMock(), $this->getMappingMock()));
    }

    public function testQuery()
    {
        $repository = new DataRepository($this->storageContainerMock);
        $repository
            ->setRussianDollKey($this->getRussianDollKey())
            ->setIdentityMapKey('some_key');
        $response = $repository->query("select * from  " . self::TABLE_NAME);
        $this->assertFalse($response->hasData());
    }

    public function testCommand()
    {
        $repository = new DataRepository($this->storageContainerMock);
        $repository
            ->setRussianDollKey($this->getRussianDollKey())
            ->setIdentityMapKey('some_key');
        $this->assertEquals(null, $repository->command("DELETE from  " . self::TABLE_NAME . " WHERE id = 1"));
    }

    public function testSelect()
    {
        $repository = new DataRepository($this->storageContainerMock);
        $repository
            ->setIdentity($this->getIdentity())
            ->setRussianDollKey($this->getRussianDollKey())
            ->setIdentityMapKey('some_key');
        $response = $repository->select();
        $this->assertFalse($response->hasData());
    }

    public function testInsert()
    {
        $this->storageContainerMock->expects($this->exactly(1))->method('hasDatasetName')->willReturn(true);
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapperBulk');
        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $repository = new DataRepository($this->storageContainerMock);
        $repository->setMapping($this->getMappingMock());
        $this->assertEquals(null, $repository->insert());
    }

    public function testInsertBulk()
    {
        $this->storageContainerMock->expects($this->exactly(1))->method('hasDatasetName')->willReturn(true);
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapper');
        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapperBulk')->willReturn($this->getDataMapperBulkMock());

        $repository = new DataRepository($this->storageContainerMock);
        $repository->setMapping($this->getMappingMock(), $this->getMappingMock(), $this->getMappingMock());
        $this->assertEquals(null, $repository->insert());
    }

    public function testUpsert()
    {
        $this->storageContainerMock->expects($this->exactly(1))->method('hasDatasetName')->willReturn(true);
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapperBulk');
        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $repository = new DataRepository($this->storageContainerMock);
        $repository->setMapping($this->getMappingMock());
        $this->assertEquals(null, $repository->upsert());
    }

    public function testUpdate()
    {
        $this->storageContainerMock->expects($this->exactly(1))->method('hasDatasetName')->willReturn(true);
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapperBulk');
        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $repository = new DataRepository($this->storageContainerMock);
        $repository->setIdentity($this->getIdentity());
        $repository->setMapping($this->getMappingMock());
        $this->assertEquals(null, $repository->update());
    }

    public function testDelete()
    {
        $this->storageContainerMock->expects($this->exactly(1))->method('hasDatasetName')->willReturn(true);
        $this->storageContainerMock->expects($this->exactly(0))->method('getDataMapperBulk');
        $this->storageContainerMock->expects($this->exactly(1))->method('getDataMapper')->willReturn($this->getDataMapperMock());

        $repository = new DataRepository($this->storageContainerMock);
        $repository->setIdentity($this->getIdentity());
        $this->assertEquals(null, $repository->delete());
    }

    private function getMappingMock()
    {
        return  $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getIdentity()
    {
        return  $this->getMockBuilder(\G4\DataMapper\Common\IdentityInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getRussianDollKey()
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
}