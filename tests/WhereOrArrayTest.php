<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereOrArrayTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserEmpty()
    {
        $data = [
            'where_or' => [],
        ];
        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        $this->assertNull($queryServer->getQueryWhereOrs());
    }

    /**
     * 表达式
     * @throws ParserException
     */
    public function testParserExp()
    {
        $data = [
            'where_or' => [
                'created_at_1{eq}' => '=', //等于
                'created_at_2{neq}' => '<>',//不等于
                'created_at_3{gt}' => '>',//大于
                'created_at_4{egt}' => '>=',//大于等于
                'created_at_5{lt}' => '<',//小于
                'created_at_6{elt}' => '<=',//小于等于
                'created_at_7{like}' => 'like',//同sql like
                'created_at_8{in}' => 'in',//同 sql in
                'created_at_9{between}' => 'between',//同 sql between
                'created_at_10' => '=',//等于
            ],
        ];
      
        $queryServer = new QueryServer(OrmEntity::createEntity($data));

        //验证表达式
        $this->assertEquals('=', $queryServer->getQueryWhereOrs()[0]->toArray()[1]);
        $this->assertEquals('<>', $queryServer->getQueryWhereOrs()[1]->toArray()[1]);
        $this->assertEquals('>', $queryServer->getQueryWhereOrs()[2]->toArray()[1]);
        $this->assertEquals('>=', $queryServer->getQueryWhereOrs()[3]->toArray()[1]);
        $this->assertEquals('<', $queryServer->getQueryWhereOrs()[4]->toArray()[1]);
        $this->assertEquals('<=', $queryServer->getQueryWhereOrs()[5]->toArray()[1]);
        $this->assertEquals('like', $queryServer->getQueryWhereOrs()[6]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWhereOrs()[7]->toArray()[1]);
        $this->assertEquals('between', $queryServer->getQueryWhereOrs()[8]->toArray()[1]);
        $this->assertEquals('=', $queryServer->getQueryWhereOrs()[9]->toArray()[1]);

    }

    /**
     * 字段
     * @throws ParserException
     */
    public function testParserField()
    {
        $data = [
            'where_or' => [
                'created_at_1{eq}' => '=', //等于
                'created_at_2{neq}' => '<>',//不等于
                'created_at_3{gt}' => '>',//大于
                'created_at_4{egt}' => '>=',//大于等于
                'created_at_5{lt}' => '<',//小于
                'created_at_6{elt}' => '<=',//小于等于
                'created_at_7{like}' => 'like',//同sql like
                'created_at_8{in}' => 'in',//同 sql in
                'created_at_9{between}' => 'between',//同 sql between
                'created_at_10' => '=',//等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        //验证表达式
        $this->assertEquals('created_at_1', $queryServer->getQueryWhereOrs()[0]->toArray()[0]);
        $this->assertEquals('created_at_2', $queryServer->getQueryWhereOrs()[1]->toArray()[0]);
        $this->assertEquals('created_at_3', $queryServer->getQueryWhereOrs()[2]->toArray()[0]);
        $this->assertEquals('created_at_4', $queryServer->getQueryWhereOrs()[3]->toArray()[0]);
        $this->assertEquals('created_at_5', $queryServer->getQueryWhereOrs()[4]->toArray()[0]);
        $this->assertEquals('created_at_6', $queryServer->getQueryWhereOrs()[5]->toArray()[0]);
        $this->assertEquals('created_at_7', $queryServer->getQueryWhereOrs()[6]->toArray()[0]);
        $this->assertEquals('created_at_8', $queryServer->getQueryWhereOrs()[7]->toArray()[0]);
        $this->assertEquals('created_at_9', $queryServer->getQueryWhereOrs()[8]->toArray()[0]);
        $this->assertEquals('created_at_10', $queryServer->getQueryWhereOrs()[9]->toArray()[0]);
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserValue()
    {
        $data = [
            'where_or' => [
                'created_at_1{eq}' => '=', //等于
                'created_at_2{neq}' => '<>',//不等于
                'created_at_3{gt}' => '>',//大于
                'created_at_4{egt}' => '>=',//大于等于
                'created_at_5{lt}' => '<',//小于
                'created_at_6{elt}' => '<=',//小于等于
                'created_at_7{like}' => 'like',//同sql like
                'created_at_8{in}' => 'in',//同 sql in
                'created_at_9{between}' => 'between',//同 sql between
                'created_at_10' => '=',//等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        //验证表达式
        $this->assertEquals('=', $queryServer->getQueryWhereOrs()[0]->toArray()[2]);
        $this->assertEquals('<>', $queryServer->getQueryWhereOrs()[1]->toArray()[2]);
        $this->assertEquals('>', $queryServer->getQueryWhereOrs()[2]->toArray()[2]);
        $this->assertEquals('>=', $queryServer->getQueryWhereOrs()[3]->toArray()[2]);
        $this->assertEquals('<', $queryServer->getQueryWhereOrs()[4]->toArray()[2]);
        $this->assertEquals('<=', $queryServer->getQueryWhereOrs()[5]->toArray()[2]);
        $this->assertEquals('like', $queryServer->getQueryWhereOrs()[6]->toArray()[2]);
        $this->assertEquals('in', $queryServer->getQueryWhereOrs()[7]->toArray()[2]);
        $this->assertEquals('between', $queryServer->getQueryWhereOrs()[8]->toArray()[2]);
        $this->assertEquals('=', $queryServer->getQueryWhereOrs()[9]->toArray()[2]);
    }
}
