<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;
use mdao\QueryOrmServer\Config;


class WhereArrayByAddTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserAdd()
    {

        $data = [
            'filter' => [
            ],
        ];
        $queryServer = new QueryServer(OrmEntity::createEntity($data));
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
        $this->assertEquals([6,4], $queryServer->getQueryWheres()[4]->valueToArray());
    }
}
