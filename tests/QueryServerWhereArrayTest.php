<?php


namespace mdao\QueryOrmServer\Test;


use mdao\QueryOrmServer\Servers\QueryServer;
use PHPUnit\Framework\TestCase;

class QueryServerWhereArrayTest extends TestCase
{
    /**
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testWhereExp()
    {
        $queryServer = QueryServer::create([
            'select' => 'ee as cc,dd:bb,ddd as e,dd'
        ]);

        $queryServer->where([['field_1', '=', '10']]);
        $queryServer->where([['field_2', '>=', '10']]);
        $queryServer->where([
            ['field_3', '!=', '10'],
            ['field_4', '<=', '10'],
        ]);


        $this->assertEquals('=', $queryServer->getQueryWheres()[0]->toArray()[1]);
        $this->assertEquals('>=', $queryServer->getQueryWheres()[1]->toArray()[1]);
        $this->assertEquals('!=', $queryServer->getQueryWheres()[2]->toArray()[1]);
        $this->assertEquals('<=', $queryServer->getQueryWheres()[3]->toArray()[1]);
    }
}
