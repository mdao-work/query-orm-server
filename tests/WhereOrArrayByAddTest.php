<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereOrArrayByAddTest extends TestCase
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
        $queryServer->whereOr('age', '=', '10');
        $queryServer->whereOr('sex', '=', '2');
        $queryServer->whereOrIn('type', [1, 2]);
        $queryServer->whereOrBetween('status', [6, 4]);
        $queryServer->whereOrNoBetween('status2', [6, 4]);

        //验证字段
        $this->assertEquals('age', $queryServer->getQueryWhereOrs()[0]->toArray()[0]);
        $this->assertEquals('sex', $queryServer->getQueryWhereOrs()[1]->toArray()[0]);
        $this->assertEquals('type', $queryServer->getQueryWhereOrs()[2]->toArray()[0]);
        $this->assertEquals('status', $queryServer->getQueryWhereOrs()[3]->toArray()[0]);
        $this->assertEquals('status2', $queryServer->getQueryWhereOrs()[4]->toArray()[0]);

        //验证表达式
        $this->assertEquals('=', $queryServer->getQueryWhereOrs()[0]->toArray()[1]);
        $this->assertEquals('=', $queryServer->getQueryWhereOrs()[1]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWhereOrs()[2]->toArray()[1]);
        $this->assertEquals('between', $queryServer->getQueryWhereOrs()[3]->toArray()[1]);
        $this->assertEquals('not between', $queryServer->getQueryWhereOrs()[4]->toArray()[1]);

        //验证值
        $this->assertEquals('10', $queryServer->getQueryWhereOrs()[0]->toArray()[2]);
        $this->assertEquals('2', $queryServer->getQueryWhereOrs()[1]->toArray()[2]);
        $this->assertEquals([1, 2], $queryServer->getQueryWhereOrs()[2]->valueToArray());
        $this->assertEquals([6, 4], $queryServer->getQueryWhereOrs()[3]->valueToArray());
        $this->assertEquals([6,4], $queryServer->getQueryWhereOrs()[4]->valueToArray());
    }
}
