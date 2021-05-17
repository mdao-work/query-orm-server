<?php

namespace mdao\QueryOrm\Test;

use mdao\QueryOrm\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrm\Servers\QueryServer;
use mdao\QueryOrm\Entities\OrmEntity;

class FilterArrayTest extends TestCase
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
        $this->assertNull($queryServer->getQueryFilter());
    }

    /**
     * 表达式
     * @throws ParserException
     */
    public function testParserExp()
    {
        $data = [
            'filter' => [
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
        $this->assertEquals('=', $queryServer->getQueryFilter()[0]->toArray()[1]);
        $this->assertEquals('<>', $queryServer->getQueryFilter()[1]->toArray()[1]);
        $this->assertEquals('>', $queryServer->getQueryFilter()[2]->toArray()[1]);
        $this->assertEquals('>=', $queryServer->getQueryFilter()[3]->toArray()[1]);
        $this->assertEquals('<', $queryServer->getQueryFilter()[4]->toArray()[1]);
        $this->assertEquals('<=', $queryServer->getQueryFilter()[5]->toArray()[1]);
        $this->assertEquals('like', $queryServer->getQueryFilter()[6]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryFilter()[7]->toArray()[1]);
        $this->assertEquals('between', $queryServer->getQueryFilter()[8]->toArray()[1]);
        $this->assertEquals('=', $queryServer->getQueryFilter()[9]->toArray()[1]);

    }

    /**
     * 字段
     * @throws ParserException
     */
    public function testParserField()
    {
        $data = [
            'filter' => [
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
        $this->assertEquals('created_at_1', $queryServer->getQueryFilter()[0]->toArray()[0]);
        $this->assertEquals('created_at_2', $queryServer->getQueryFilter()[1]->toArray()[0]);
        $this->assertEquals('created_at_3', $queryServer->getQueryFilter()[2]->toArray()[0]);
        $this->assertEquals('created_at_4', $queryServer->getQueryFilter()[3]->toArray()[0]);
        $this->assertEquals('created_at_5', $queryServer->getQueryFilter()[4]->toArray()[0]);
        $this->assertEquals('created_at_6', $queryServer->getQueryFilter()[5]->toArray()[0]);
        $this->assertEquals('created_at_7', $queryServer->getQueryFilter()[6]->toArray()[0]);
        $this->assertEquals('created_at_8', $queryServer->getQueryFilter()[7]->toArray()[0]);
        $this->assertEquals('created_at_9', $queryServer->getQueryFilter()[8]->toArray()[0]);
        $this->assertEquals('created_at_10', $queryServer->getQueryFilter()[9]->toArray()[0]);
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserValue()
    {
        $data = [
            'filter' => [
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
        $this->assertEquals('=', $queryServer->getQueryFilter()[0]->toArray()[2]);
        $this->assertEquals('<>', $queryServer->getQueryFilter()[1]->toArray()[2]);
        $this->assertEquals('>', $queryServer->getQueryFilter()[2]->toArray()[2]);
        $this->assertEquals('>=', $queryServer->getQueryFilter()[3]->toArray()[2]);
        $this->assertEquals('<', $queryServer->getQueryFilter()[4]->toArray()[2]);
        $this->assertEquals('<=', $queryServer->getQueryFilter()[5]->toArray()[2]);
        $this->assertEquals('like', $queryServer->getQueryFilter()[6]->toArray()[2]);
        $this->assertEquals('in', $queryServer->getQueryFilter()[7]->toArray()[2]);
        $this->assertEquals('between', $queryServer->getQueryFilter()[8]->toArray()[2]);
        $this->assertEquals('=', $queryServer->getQueryFilter()[9]->toArray()[2]);

    }
}