<?php

use G4\DataRepository\SimpleDataRepositoryResponse;

class SimpleDataRepositoryResponseTest extends \PHPUnit\Framework\TestCase
{
    private $data;

    private $response;

    public function setUp(): void
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

        $this->response = new SimpleDataRepositoryResponse($this->data, 2);
    }

    public function tearDown(): void
    {
        $this->data     = null;
        $this->response = null;
    }

    public function testHasData()
    {
        $this->assertTrue($this->response->hasData());
    }

    public function testCount()
    {
        $this->assertEquals(2, $this->response->count());
    }

    public function testGetAll()
    {
        $this->assertEquals($this->data, $this->response->getAll());
    }

    public function testGetOne()
    {
        $this->assertEquals($this->data[0], $this->response->getOne());
    }

    public function testHasDataForEmpty()
    {
        $this->assertFalse($this->emptyResponseFactory()->hasData());
    }

    public function testCountForEmpty()
    {
        $this->assertEquals(0, $this->emptyResponseFactory()->count());
    }

    public function testGetAllForEmpty()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingResponseAllDataException::class);
        $this->emptyResponseFactory()->getAll();
    }

    public function testGetOneException()
    {
        $this->expectException(\G4\DataRepository\Exception\MissingResponseOneDataException::class);
        $this->emptyResponseFactory()->getOne();
    }

    private function emptyResponseFactory(){
        return new SimpleDataRepositoryResponse([], 0);
    }

}