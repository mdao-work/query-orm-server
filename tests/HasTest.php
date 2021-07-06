<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class HasTest extends TestCase
{

    /**
     * @throws ParserException
     */
    public function testHasWhereField()
    {
        $data = [
            'filter' => [
                'field_1{eq}' => '=', //等于
                'field_2{neq}' => '<>',//不等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));

        $this->assertEquals(true, $queryServer->hasWhereField('field_1'));
        $this->assertEquals(true, $queryServer->hasWhereField('field_2'));
        $this->assertEquals(true, $queryServer->hasWhereField(['field_1', 'field_2']));
        $this->assertEquals(true, $queryServer->hasWhereField('field_1', 'field_2'));
        $this->assertEquals(false, $queryServer->hasWhereField('field_3'));
        $this->assertEquals(false, $queryServer->hasWhereField(['field_1', 'field_3']));
        $this->assertEquals(false, $queryServer->hasWhereField('field_1', 'field_3'));
        $this->assertEquals(false, $queryServer->hasWhereField(['field_3', 'field_5']));
    }

    /**
     * @throws ParserException
     */
    public function testHasWhereOrField()
    {
        $data = [
            'where_or' => [
                'field_1{eq}' => '=', //等于
                'field_2{neq}' => '<>',//不等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));

        $this->assertEquals(true, $queryServer->hasWhereOrField('field_1'));
        $this->assertEquals(true, $queryServer->hasWhereOrField('field_2'));
        $this->assertEquals(true, $queryServer->hasWhereOrField(['field_1', 'field_2']));
        $this->assertEquals(true, $queryServer->hasWhereOrField('field_1', 'field_2'));
        $this->assertEquals(false, $queryServer->hasWhereOrField('field_3'));
        $this->assertEquals(false, $queryServer->hasWhereOrField(['field_1', 'field_3']));
        $this->assertEquals(false, $queryServer->hasWhereOrField('field_1', 'field_3'));
        $this->assertEquals(false, $queryServer->hasWhereOrField(['field_3', 'field_5']));
    }

    /**
     * @throws ParserException
     */
    public function testHasOrderField()
    {
        //url?order_by=id,type&sorted_by=desc,asc
        $url = "https://www.baidu.com?order_by=field_1,field_2&sorted_by=desc,asc";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        $this->assertEquals(true, $queryServer->hasOrderField('field_1'));
        $this->assertEquals(true, $queryServer->hasOrderField('field_2'));
        $this->assertEquals(true, $queryServer->hasOrderField(['field_1', 'field_2']));
        $this->assertEquals(true, $queryServer->hasOrderField('field_1', 'field_2'));
        $this->assertEquals(false, $queryServer->hasOrderField('field_3'));
        $this->assertEquals(false, $queryServer->hasOrderField(['field_1', 'field_3']));
        $this->assertEquals(false, $queryServer->hasOrderField('field_1', 'field_3'));
        $this->assertEquals(false, $queryServer->hasOrderField(['field_3', 'field_5']));
    }

    /**
     * @throws ParserException
     */
    public function testHasSelect()
    {
        $url = "https://www.baidu.com?select=,field_1,field_2,content:text";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        $this->assertEquals(true, $queryServer->hasSelect('field_1'));
        $this->assertEquals(true, $queryServer->hasSelect('field_2'));
        $this->assertEquals(true, $queryServer->hasSelect(['field_1', 'field_2']));
        $this->assertEquals(true, $queryServer->hasSelect('field_1', 'field_2'));
        $this->assertEquals(false, $queryServer->hasSelect('field_3'));
        $this->assertEquals(false, $queryServer->hasSelect(['field_1', 'field_3']));
        $this->assertEquals(false, $queryServer->hasSelect('field_1', 'field_3'));
        $this->assertEquals(false, $queryServer->hasSelect(['field_3', 'field_5']));

    }

}
