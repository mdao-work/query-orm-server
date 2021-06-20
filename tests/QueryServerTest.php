<?php

namespace mdao\QueryOrmServer\Test;

use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class QueryServerTest extends TestCase
{
    /**
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testParserEmpty()
    {
        $queryServer = QueryServer::create();

        $queryServer->where('age', '=', '10');
        $queryServer->where('sex', '=', '2');
        $queryServer->whereIn('type', [1, 2]);
        $queryServer->whereBetween('status', [6, 4]);
        $queryServer->whereNoBetween('status2', [6, 4]);

        //验证字段
        $this->assertEquals('age', $queryServer->getQueryWheres()[0]->toArray()[0]);
        $this->assertEquals('sex', $queryServer->getQueryWheres()[1]->toArray()[0]);
        $this->assertEquals('type', $queryServer->getQueryWheres()[2]->toArray()[0]);
        $this->assertEquals('status', $queryServer->getQueryWheres()[3]->toArray()[0]);
        $this->assertEquals('status2', $queryServer->getQueryWheres()[4]->toArray()[0]);

        //验证表达式
        $this->assertEquals('=', $queryServer->getQueryWheres()[0]->toArray()[1]);
        $this->assertEquals('=', $queryServer->getQueryWheres()[1]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWheres()[2]->toArray()[1]);
        $this->assertEquals('between', $queryServer->getQueryWheres()[3]->toArray()[1]);
        $this->assertEquals('not between', $queryServer->getQueryWheres()[4]->toArray()[1]);

        //验证值
        $this->assertEquals('10', $queryServer->getQueryWheres()[0]->toArray()[2]);
        $this->assertEquals('2', $queryServer->getQueryWheres()[1]->toArray()[2]);
        $this->assertEquals([1, 2], $queryServer->getQueryWheres()[2]->valueToArray());
        $this->assertEquals([6, 4], $queryServer->getQueryWheres()[3]->valueToArray());
        $this->assertEquals([6, 4], $queryServer->getQueryWheres()[4]->valueToArray());
    }

    /**
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testParserOrder()
    {
        $queryServer = QueryServer::create();

        $queryServer->orderBy('age');
        $queryServer->orderBy('id', 'asc');

        $this->assertEquals('age', $queryServer->getQueryOrderBy()->toArray()[0][0]);
        $this->assertEquals('id', $queryServer->getQueryOrderBy()->toArray()[1][0]);

        $this->assertEquals('desc', $queryServer->getQueryOrderBy()->toArray()[0][1]);
        $this->assertEquals('asc', $queryServer->getQueryOrderBy()->toArray()[1][1]);
    }

    /**
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testToArray()
    {
        $queryServer = QueryServer::create();

        $queryServer->where('age', '=', '10');
        $queryServer->where('sex', '=', '2');
        $queryServer->whereOr('age_or', '=', '10');
        $queryServer->whereOr('sex_or', '=', '2');
        $queryServer->addSelect(['sex_or', 'age']);
        $queryServer->page(1, 20);
        $queryServer->orderBy('age');
        $queryServer->orderBy('id', 'asc');
        $queryServerArray = $queryServer->toArray();

        $this->assertEquals([
            "age" => [
                "field" => "age",
                "operator" => "=",
                "value" => "10",
            ],
            "sex" => [
                "field" => "sex",
                "operator" => "=",
                "value" => "2",
            ]
        ], $queryServerArray['filter']);

        $this->assertEquals([
            "age_or" => [
                "field" => "age_or",
                "operator" => "=",
                "value" => "10",
            ],
            "sex_or" => [
                "field" => "sex_or",
                "operator" => "=",
                "value" => "2",
            ]
        ], $queryServerArray['where_or']);

        $this->assertEquals([
            ["age", "desc"], ["id", "asc"]
        ], $queryServerArray['order']);


        $this->assertEquals(["sex_or", "age"], $queryServerArray['select']);
        $this->assertEquals(20, $queryServerArray['page']);
        $this->assertEquals(1, $queryServerArray['page_size']);

    }

    /**
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testSelectToArray()
    {
        $queryServer = QueryServer::create();
        $queryServer->addSelect(['sex_or', 'age', 'text as b']);
        $queryServerArray = $queryServer->toArray();
        $this->assertEquals(["sex_or", "age"], $queryServerArray['select']);

    }
}
