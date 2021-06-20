<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class OrderTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserEmpty()
    {
        $queryServer = new QueryServer(OrmEntity::createEntity([]));
        $this->assertNull($queryServer->getQueryOrderBy());
    }

    /**
     * 降序排列
     * @throws ParserException
     */
    public function testParserDesc()
    {
        $url = "https://www.baidu.com?order_by=id&sorted_by=desc";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        //验证表达式
        $this->assertEquals(['id', 'desc'], $queryServer->getQueryOrderBy()[0]->toArray());
    }

    /**
     * 升序排列
     * @throws ParserException
     */
    public function testParserAsc()
    {
        $url = "https://www.baidu.com?order_by=id&sorted_by=asc";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        $this->assertEquals(['id', 'asc'], $queryServer->getQueryOrderBy()[0]->toArray());
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserFieldsDesc()
    {
        //url?order_by=id,type&sorted_by=desc,asc
        $url = "https://www.baidu.com?order_by=id,type&sorted_by=desc,asc";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        $this->assertEquals(['id', 'desc'], $queryServer->getQueryOrderBy()[0]->toArray());

        $this->assertEquals(['type', 'asc'], $queryServer->getQueryOrderBy()[1]->toArray());
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserOrderDefault()
    {
        //url?order_by=id,type&sorted_by=desc,asc
        $url = "https://www.baidu.com?order_by=id&sorted_by=asc，";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));
        $this->assertEquals(['id', 'asc'], $queryServer->getQueryOrderBy()[0]->toArray());
    }
}
