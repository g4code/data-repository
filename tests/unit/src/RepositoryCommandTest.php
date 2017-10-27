<?php

use G4\DataRepository\RepositoryCommand;
use G4\DataMapper\Common\MappingInterface;
use G4\RussianDoll\Key;
use G4\DataMapper\Common\IdentityInterface;

class RepositoryCommandTest extends \PHPUnit_Framework_TestCase
{
    const IDENTITY_MAP_KEY = 'some_key';

    const CUSTOM_COMMAND = 'DELETE from __taable_name__';
    /**
     * @var RepositoryCommand
     */
    private $command;

    public function setUp()
    {
        $this->command = new RepositoryCommand();
    }

    public function tearDown()
    {
        $this->command = null;
    }

    public function testGetRussianDollKey()
    {
        $this->command->setRussianDollKey($this->getRussianDollKeyMock());
        $this->assertInstanceOf(Key::class, $this->command->getRussianDollKey());
    }

    public function testGetRussianDollKeyException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingRussianDollKeyException::class);
        $this->command->getRussianDollKey();
    }

    public function testGetIdentityMapKey()
    {
        $this->command->setIdentityMapKey(self::IDENTITY_MAP_KEY);
        $this->assertEquals(self::IDENTITY_MAP_KEY, $this->command->getIdentityMapKey());
    }

    public function testGetIdentityMapKeyException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingIdentityMapKeyException::class);
        $this->command->getIdentityMapKey();
    }

    public function testSetCustomCommand()
    {
        $this->assertTrue(true, $this->command->setCustomCommand(self::CUSTOM_COMMAND)->isCustomCommand());
    }

    public function testGetCustomCommand()
    {
        $this->command->setCustomCommand(self::CUSTOM_COMMAND);
        $this->assertEquals(self::CUSTOM_COMMAND, $this->command->getCustomCommand());
    }

    public function testGetCustomCommandException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingCustomCommandException::class);
        $this->command->getCustomCommand();
    }

    public function testIsUpsert()
    {
        $this->assertTrue(
            true,
            $this->command->update($this->getMappingMock(), $this->getIdentityMock())->isUpsert()
        );
    }

    public function testIsDelete()
    {
        $this->assertTrue(
            true,
            $this->command->delete($this->getIdentityMock())->isDelete()
        );
    }

    public function testIsInsert()
    {
        $this->assertTrue(
            true,
            $this->command->insert($this->getMappingMock())->isInsert()
        );
    }

    public function testIsUpdate()
    {
        $this->assertTrue(
            true,
            $this->command->update($this->getMappingMock(), $this->getIdentityMock())->isUpdate()
        );
    }

    public function testGetAction()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingActionException::class);
        $this->command->isCustomCommand();
    }

    public function testGetMap()
    {
        $map = $this->command->update($this->getMappingMock(), $this->getIdentityMock())->getMap();
        $this->assertInstanceOf(MappingInterface::class, $map);
    }

    public function testGetMapException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingMapperException::class);
        $this->command->getMap();
    }

    public function testGetIdentity()
    {
        $identity = $this->command->delete($this->getIdentityMock())->getIdentity();
        $this->assertInstanceOf(IdentityInterface::class, $identity);
    }

    public function testGetIdentityException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingIdentityException::class);
        $this->command->getIdentity();
    }

    private function getRussianDollKeyMock()
    {
        return $this->getMockBuilder(Key::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getMappingMock()
    {
        return $this->getMockBuilder(MappingInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getIdentityMock()
    {
        return $this->getMockBuilder(IdentityInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

}