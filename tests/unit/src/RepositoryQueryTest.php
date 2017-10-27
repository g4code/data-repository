<?php

use G4\DataRepository\RepositoryQuery;
use \G4\DataMapper\Common\IdentityInterface;
use \G4\RussianDoll\Key;

class RepositoryQueryTest extends \PHPUnit_Framework_TestCase
{
    const IDENTITY_MAP_KEY = 'some_key';

    const CUSTOM_SELECT_QUERY = 'select * from __taable_name__';

    /**
     * @var RepositoryQuery
     */
    private $query;

    public function setUp()
    {
        $this->query = new RepositoryQuery();
    }

    public function tearDown()
    {
        $this->query = null;
    }

    public function testGetIdentity()
    {
        $this
            ->query
            ->setIdentity($this->getIdentityMock());
        $this->assertInstanceOf(IdentityInterface::class, $this->query->getIdentity());
    }

    public function testGetIdentityException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingIdentityException::class);
        $this->query->getIdentity();
    }

    public function testGetRussianDollKey()
    {
        $this
            ->query
            ->setRussianDollKey($this->getRussianDollKeyMock());
        $this->assertInstanceOf(Key::class, $this->query->getRussianDollKey());
    }

    public function testGetRussianDollKeyException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingRussianDollKeyException::class);
        $this->query->getRussianDollKey();
    }

    public function testGetIdentityMapKey()
    {
        $this
            ->query
            ->setIdentityMapKey(self::IDENTITY_MAP_KEY);
        $this->assertEquals(self::IDENTITY_MAP_KEY, $this->query->getIdentityMapKey());
    }

    public function testGetIdentityMapKeyException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingIdentityMapKeyException::class);
        $this->query->getIdentityMapKey();
    }

    public function testGetCustomQuery()
    {
        $this
            ->query
            ->setCustomQuery(self::CUSTOM_SELECT_QUERY);
        $this->assertEquals(self::CUSTOM_SELECT_QUERY, $this->query->getCustomQuery());
    }

    public function testGetCustomQueryException()
    {
        $this->expectException(\G4\DataRepository\Exception\InvalidQueryException::class);
        $this->query->getCustomQuery();
    }


    private function getIdentityMock()
    {
        return $this->getMockBuilder(IdentityInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getRussianDollKeyMock()
    {
        return $this->getMockBuilder(Key::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}