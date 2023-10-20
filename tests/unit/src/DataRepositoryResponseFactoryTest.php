<?php

use G4\DataRepository\RepositoryResponseFactory;
use G4\DataRepository\DataRepositoryResponse;
use G4\DataRepository\SimpleDataRepositoryResponse;

class DataRepositoryResponseFactoryTest extends \PHPUnit\Framework\TestCase
{

    public function testCreate()
    {
        $responseFactory = new RepositoryResponseFactory();
        $rawData = $this->getMockBuilder(\G4\DataMapper\Common\RawData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rawData->method('getAll')->willReturn([]);
        $rawData->method('count')->willReturn(1);
        $rawData->method('getTotal')->willReturn(2);

        $this->assertInstanceOf(DataRepositoryResponse::class, $responseFactory->create($rawData));
    }

    public function testCreateFromArray()
    {
        $responseFactory = new RepositoryResponseFactory();
        $data = $this->getMockBuilder(\G4\ValueObject\Dictionary::class)
            ->disableOriginalConstructor()
            ->getMock();
        $data->method('get')->willReturn([]);

        $this->assertInstanceOf(DataRepositoryResponse::class, $responseFactory->createFromArray($data));
    }

    public function testCreateSimple()
    {
        $responseFactory = new RepositoryResponseFactory();
        $rawData = $this->getMockBuilder(\G4\DataMapper\Common\SimpleRawData::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rawData->expects($this->once())->method('getAll')->willReturn(['key' => 'value']);
        $rawData->expects($this->once())->method('count')->willReturn(1);

        $this->assertInstanceOf(SimpleDataRepositoryResponse::class, $responseFactory->createSimple($rawData));
    }
}