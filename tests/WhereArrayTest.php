<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereArrayTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserEmpty()
    {
        $data = [
            'filter' => [],
        ];
        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        $this->assertNull($queryServer->getQueryWheres());
    }

    /**
     * 表达式
     * @throws ParserException
     */
    public function testParserExp()
    {
        $data = [
            'filter' => [
                'field_1{eq}' => '=', //等于
                'field_2{neq}' => '<>',//不等于
                'field_3{gt}' => '>',//大于
                'field_4{egt}' => '>=',//大于等于
                'field_5{lt}' => '<',//小于
                'field_6{elt}' => '<=',//小于等于
                'field_7{like}' => 'like',//同sql like
                'field_8{in}' => 'in',//同 sql in
                'field_9{between}' => 'between',//同 sql between
                'field_10' => '=',//等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        //验证表达式
        $this->assertEquals('=', $queryServer->getQueryWheres()[0]->toArray()[1]);
        $this->assertEquals('<>', $queryServer->getQueryWheres()[1]->toArray()[1]);
        $this->assertEquals('>', $queryServer->getQueryWheres()[2]->toArray()[1]);
        $this->assertEquals('>=', $queryServer->getQueryWheres()[3]->toArray()[1]);
        $this->assertEquals('<', $queryServer->getQueryWheres()[4]->toArray()[1]);
        $this->assertEquals('<=', $queryServer->getQueryWheres()[5]->toArray()[1]);
        $this->assertEquals('like', $queryServer->getQueryWheres()[6]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWheres()[7]->toArray()[1]);
        $this->assertEquals('between', $queryServer->getQueryWheres()[8]->toArray()[1]);
        $this->assertEquals('=', $queryServer->getQueryWheres()[9]->toArray()[1]);
    }

    /**
     * 字段
     * @throws ParserException
     */
    public function testParserField()
    {
        $data = [
            'filter' => [
                'field_1{eq}' => '=', //等于
                'field_2{neq}' => '<>',//不等于
                'field_3{gt}' => '>',//大于
                'field_4{egt}' => '>=',//大于等于
                'field_5{lt}' => '<',//小于
                'field_6{elt}' => '<=',//小于等于
                'field_7{like}' => 'like',//同sql like
                'field_8{in}' => 'in',//同 sql in
                'field_9{between}' => 'between',//同 sql between
                'field_10' => '=',//等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        //验证表达式
        $this->assertEquals('field_1', $queryServer->getQueryWheres()[0]->toArray()[0]);
        $this->assertEquals('field_2', $queryServer->getQueryWheres()[1]->toArray()[0]);
        $this->assertEquals('field_3', $queryServer->getQueryWheres()[2]->toArray()[0]);
        $this->assertEquals('field_4', $queryServer->getQueryWheres()[3]->toArray()[0]);
        $this->assertEquals('field_5', $queryServer->getQueryWheres()[4]->toArray()[0]);
        $this->assertEquals('field_6', $queryServer->getQueryWheres()[5]->toArray()[0]);
        $this->assertEquals('field_7', $queryServer->getQueryWheres()[6]->toArray()[0]);
        $this->assertEquals('field_8', $queryServer->getQueryWheres()[7]->toArray()[0]);
        $this->assertEquals('field_9', $queryServer->getQueryWheres()[8]->toArray()[0]);
        $this->assertEquals('field_10', $queryServer->getQueryWheres()[9]->toArray()[0]);
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserValue()
    {
        $data = [
            'filter' => [
                'field_1{eq}' => '=', //等于
                'field_2{neq}' => '<>',//不等于
                'field_3{gt}' => '>',//大于
                'field_4{egt}' => '>=',//大于等于
                'field_5{lt}' => '<',//小于
                'field_6{elt}' => '<=',//小于等于
                'field_7{like}' => 'like',//同sql like
                'field_8{in}' => 'in',//同 sql in
                'field_9{between}' => 'between',//同 sql between
                'field_10' => '=',//等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        //验证表达式
        $this->assertEquals('=', $queryServer->getQueryWheres()[0]->toArray()[2]);
        $this->assertEquals('<>', $queryServer->getQueryWheres()[1]->toArray()[2]);
        $this->assertEquals('>', $queryServer->getQueryWheres()[2]->toArray()[2]);
        $this->assertEquals('>=', $queryServer->getQueryWheres()[3]->toArray()[2]);
        $this->assertEquals('<', $queryServer->getQueryWheres()[4]->toArray()[2]);
        $this->assertEquals('<=', $queryServer->getQueryWheres()[5]->toArray()[2]);
        $this->assertEquals('like', $queryServer->getQueryWheres()[6]->toArray()[2]);
        $this->assertEquals('in', $queryServer->getQueryWheres()[7]->toArray()[2]);
        $this->assertEquals('between', $queryServer->getQueryWheres()[8]->toArray()[2]);
        $this->assertEquals('=', $queryServer->getQueryWheres()[9]->toArray()[2]);
    }
}
