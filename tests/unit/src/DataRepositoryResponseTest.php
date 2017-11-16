<?php

use G4\DataRepository\DataRepositoryResponse;

class DataRepositoryResponseTest extends \PHPUnit_Framework_TestCase
{
    private $data;

    /*
     * @var \G4\DataRepository\DataRepositoryResponse
     */
    private $response;

    public function setUp()
    {
        $this->data = [
            [
                'user_id'   => 1,
                'username'  => 'username1',
                'email'     => 'test1@tt.tt'
            ],
            [
                'user_id'   => 2,
                'username'  => 'username2',
                'email'     => 'test2@tt.tt'
            ],
        ];

        $this->response = new DataRepositoryResponse($this->data, 2, 4);
    }

    public function tearDown()
    {
        $this->data     = null;
        $this->response = null;
    }

    public function testCount()
    {
        $this->assertEquals(2, $this->response->count());
    }

    public function testTotal()
    {
        $this->assertEquals(4, $this->response->getTotal());
    }

    public function testGetAll()
    {
        $this->assertEquals($this->data, $this->response->getAll());
    }

    public function testGetOne()
    {
        $this->assertEquals($this->data[0], $this->response->getOne());
    }

    public function testHasData()
    {
        $this->assertEquals(true, $this->response->hasData());
    }

}