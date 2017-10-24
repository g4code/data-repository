<?php

use G4\DataRepository\RepositoryResponseFormatter;
use G4\DataRepository\DataRepositoryResponse;

class RepositoryResponseFormatterTest extends \PHPUnit_Framework_TestCase
{

    public function testFormat()
    {
        $response = $this->getMockBuilder(DataRepositoryResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $response->method('getTotal')->willReturn(0);
        $response->method('count')->willReturn(0);
        $response->method('getAll')->willReturn([]);

        $formatter = new RepositoryResponseFormatter($response);

        $this->assertEquals([
            'total' => 0,
            'count' => 0,
            'data'  => []
        ], $formatter->format());

    }

}