<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Entities\QueryWheres;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereOrTest extends TestCase
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
        $QueryWheres = $queryServer->getQueryWhereOrs();
        //验证表达式
        $this->assertEquals('=', $QueryWheres['field_1']->getOperator());
        $this->assertEquals('<>', $QueryWheres['field_2']->getOperator());
        $this->assertEquals('>', $QueryWheres['field_3']->getOperator());
        $this->assertEquals('>=', $QueryWheres['field_4']->getOperator());
        $this->assertEquals('<', $QueryWheres['field_5']->getOperator());
        $this->assertEquals('<=', $QueryWheres['field_6']->getOperator());
        $this->assertEquals('like', $QueryWheres['field_7']->getOperator());
        $this->assertEquals('in', $QueryWheres['field_8']->getOperator());
        $this->assertEquals('between', $QueryWheres['field_9']->getOperator());
        $this->assertEquals('=', $QueryWheres['field_10']->getOperator());
    }

    /**
     * 字段
     * @throws ParserException
     */
    public function testParserField()
    {
        $data = [
            'where_or' => [
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
        $QueryWheres = $queryServer->getQueryWhereOrs();
        //验证字段
        $this->assertEquals('field_1', $QueryWheres['field_1']->getField());
        $this->assertEquals('field_2', $QueryWheres['field_2']->getField());
        $this->assertEquals('field_3', $QueryWheres['field_3']->getField());
        $this->assertEquals('field_4', $QueryWheres['field_4']->getField());
        $this->assertEquals('field_5', $QueryWheres['field_5']->getField());
        $this->assertEquals('field_6', $QueryWheres['field_6']->getField());
        $this->assertEquals('field_7', $QueryWheres['field_7']->getField());
        $this->assertEquals('field_8', $QueryWheres['field_8']->getField());
        $this->assertEquals('field_9', $QueryWheres['field_9']->getField());
        $this->assertEquals('field_10', $QueryWheres['field_10']->getField());
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserValue()
    {
        $data = [
            'where_or' => [
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
        $QueryWheres = $queryServer->getQueryWhereOrs();
        //验证值
        $this->assertEquals('=', $QueryWheres['field_1']->getValue());
        $this->assertEquals('<>', $QueryWheres['field_2']->getValue());
        $this->assertEquals('>', $QueryWheres['field_3']->getValue());
        $this->assertEquals('>=', $QueryWheres['field_4']->getValue());
        $this->assertEquals('<', $QueryWheres['field_5']->getValue());
        $this->assertEquals('<=', $QueryWheres['field_6']->getValue());
        $this->assertEquals('like', $QueryWheres['field_7']->getValue());
        $this->assertEquals('in', $QueryWheres['field_8']->getValue());
        $this->assertEquals('between', $QueryWheres['field_9']->getValue());
        $this->assertEquals('=', $QueryWheres['field_10']->getValue());
    }

    /**
     * count
     * @throws ParserException
     */
    public function testParserCount()
    {
        $data = [
            'where_or' => [
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
        $QueryWheres = $queryServer->getQueryWhereOrs();
        $this->assertCount(10, $QueryWheres);
    }

    /**
     * Json
     * @throws ParserException
     */
    public function testParserFor()
    {
        $data = [
            'where_or' => [
                'field_1{eq}' => '=', //等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        $QueryWheres = $queryServer->getQueryWhereOrs();

        foreach ($QueryWheres as $index => $queryWhere) {
            $this->assertEquals('=', $queryWhere->getValue());
            $this->assertEquals('field_1', $queryWhere->getField());
            $this->assertEquals('=', $queryWhere->getOperator());
        }
    }
}
