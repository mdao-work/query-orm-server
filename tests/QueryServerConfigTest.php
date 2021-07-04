<?php

namespace mdao\QueryOrmServer\Test;

use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class QueryServerConfigTest extends TestCase
{
    // /**
    //  * @throws \mdao\QueryOrmServer\Exception\ParserException
    //  */
    // public function testParserEmpty()
    // {
    //     $data = [
    //         'or_test' => [
    //             'created_at_1{eq}' => '=', //等于
    //             'created_at_2{neq}' => '<>',//不等于
    //             'created_at_3{gt}' => '>',//大于
    //             'created_at_4{egt}' => '>=',//大于等于
    //             'created_at_5{lt}' => '<',//小于
    //             'created_at_6{elt}' => '<=',//小于等于
    //             'created_at_7{like}' => 'like',//同sql like
    //             'created_at_8{in}' => 'in',//同 sql in
    //             'created_at_9{between}' => 'between',//同 sql between
    //             'created_at_10' => '=',//等于
    //         ],
    //     ];
    //
    //     $queryServer = new QueryServer(OrmEntity::createEntity($data, ['param' => [
    //         'where_or' => 'or_test'
    //     ]]));
    //
    //
    //     //验证表达式
    //     $this->assertEquals('=', $queryServer->getQueryWhereOrs()[0]->toArray()[1]);
    //     $this->assertEquals('<>', $queryServer->getQueryWhereOrs()[1]->toArray()[1]);
    //     $this->assertEquals('>', $queryServer->getQueryWhereOrs()[2]->toArray()[1]);
    //     $this->assertEquals('>=', $queryServer->getQueryWhereOrs()[3]->toArray()[1]);
    //     $this->assertEquals('<', $queryServer->getQueryWhereOrs()[4]->toArray()[1]);
    //     $this->assertEquals('<=', $queryServer->getQueryWhereOrs()[5]->toArray()[1]);
    //     $this->assertEquals('like', $queryServer->getQueryWhereOrs()[6]->toArray()[1]);
    //     $this->assertEquals('in', $queryServer->getQueryWhereOrs()[7]->toArray()[1]);
    //     $this->assertEquals('between', $queryServer->getQueryWhereOrs()[8]->toArray()[1]);
    //     $this->assertEquals('=', $queryServer->getQueryWhereOrs()[9]->toArray()[1]);
    //
    // }

}
