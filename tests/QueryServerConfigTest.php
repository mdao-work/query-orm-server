<?php

namespace mdao\QueryOrmServer\Test;

use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class QueryServerConfigTest extends TestCase
{
    /**
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testWhereOr()
    {
        $data = [
            'or_test' => [
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

        $queryServer = new QueryServer(OrmEntity::createEntity($data, ['param' => [
            'where_or' => 'or_test'
        ]]));

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
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testWhere()
    {
        $data = [
            'where_test' => [
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

        $queryServer = new QueryServer(OrmEntity::createEntity($data, ['param' => [
            'filter' => 'where_test'
        ]]));

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
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testSelect()
    {
        $url = "https://www.baidu.com?ddd=id,date,content:text,aa:b";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery, ['param' => [
            'select' => 'ddd'
        ]]));

        //验证表达式
        $this->assertEquals('id,date,content as text,aa as b', $queryServer->getQuerySelect()->getSelectToString());
        $this->assertEquals(['id', 'date', 'content as text', 'aa as b'], $queryServer->getQuerySelect()->toArray());
        $this->assertEquals(['id', 'date', 'content as text', 'aa as b'], $queryServer->getQuerySelect()->getSelect());
        $this->assertEquals(['content' => 'text', 'aa' => 'b'], $queryServer->getQuerySelect()->getAlias());
    }

    /**
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testPage()
    {
        $url = "https://www.baidu.com?p=19&size=50";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery, ['param' => [
            'page' => 'p',
            'page_size' => 'size',
        ]]));

        //验证表达式
        $this->assertEquals([
            'page' => 19,
            'page_size' => 50,
        ], $queryServer->getQueryPagination()->toArray());

    }

    /**
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testOrder()
    {
        $url = "https://www.baidu.com?by=id&sorted=desc";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery, ['param' => [
            'order_by' => 'by',
            'sorted_by' => 'sorted',
        ]]));
        //验证表达式
        $this->assertEquals(['id', 'desc'], $queryServer->getQueryOrderBy()[0]->toArray());
    }

}
