<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Entities\QueryWheres;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereTest extends TestCase
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
        $QueryWheres = $queryServer->getQueryWheres();
        //验证表达式
        $this->assertEquals('=', $QueryWheres['created_at_1']->getOperator());
        $this->assertEquals('<>', $QueryWheres['created_at_2']->getOperator());
        $this->assertEquals('>', $QueryWheres['created_at_3']->getOperator());
        $this->assertEquals('>=', $QueryWheres['created_at_4']->getOperator());
        $this->assertEquals('<', $QueryWheres['created_at_5']->getOperator());
        $this->assertEquals('<=', $QueryWheres['created_at_6']->getOperator());
        $this->assertEquals('like', $QueryWheres['created_at_7']->getOperator());
        $this->assertEquals('in', $QueryWheres['created_at_8']->getOperator());
        $this->assertEquals('between', $QueryWheres['created_at_9']->getOperator());
        $this->assertEquals('=', $QueryWheres['created_at_10']->getOperator());
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
        $QueryWheres = $queryServer->getQueryWheres();
        //验证字段
        $this->assertEquals('created_at_1', $QueryWheres['created_at_1']->getField());
        $this->assertEquals('created_at_2', $QueryWheres['created_at_2']->getField());
        $this->assertEquals('created_at_3', $QueryWheres['created_at_3']->getField());
        $this->assertEquals('created_at_4', $QueryWheres['created_at_4']->getField());
        $this->assertEquals('created_at_5', $QueryWheres['created_at_5']->getField());
        $this->assertEquals('created_at_6', $QueryWheres['created_at_6']->getField());
        $this->assertEquals('created_at_7', $QueryWheres['created_at_7']->getField());
        $this->assertEquals('created_at_8', $QueryWheres['created_at_8']->getField());
        $this->assertEquals('created_at_9', $QueryWheres['created_at_9']->getField());
        $this->assertEquals('created_at_10', $QueryWheres['created_at_10']->getField());
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
        $QueryWheres = $queryServer->getQueryWheres();
        //验证值
        $this->assertEquals('=', $QueryWheres['created_at_1']->getValue());
        $this->assertEquals('<>', $QueryWheres['created_at_2']->getValue());
        $this->assertEquals('>', $QueryWheres['created_at_3']->getValue());
        $this->assertEquals('>=', $QueryWheres['created_at_4']->getValue());
        $this->assertEquals('<', $QueryWheres['created_at_5']->getValue());
        $this->assertEquals('<=', $QueryWheres['created_at_6']->getValue());
        $this->assertEquals('like', $QueryWheres['created_at_7']->getValue());
        $this->assertEquals('in', $QueryWheres['created_at_8']->getValue());
        $this->assertEquals('between', $QueryWheres['created_at_9']->getValue());
        $this->assertEquals('=', $QueryWheres['created_at_10']->getValue());
    }

    /**
     * count
     * @throws ParserException
     */
    public function testParserCount()
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
        $QueryWheres = $queryServer->getQueryWheres();
        $this->assertCount(10, $QueryWheres);
    }

    /**
     * Json
     * @throws ParserException
     */
    public function testParserFor()
    {
        $data = [
            'filter' => [
                'created_at_1{eq}' => '=', //等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        $QueryWheres = $queryServer->getQueryWheres();

        foreach ($QueryWheres as $index => $queryWhere) {
            $this->assertEquals('=', $queryWhere->getValue());
            $this->assertEquals('created_at_1', $queryWhere->getField());
            $this->assertEquals('=', $queryWhere->getOperator());
        }
    }
}
