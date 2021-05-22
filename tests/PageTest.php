<?php

namespace mdao\QueryOrmServer\Test;

use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class PageTest extends TestCase
{
    public function testParserEmpty()
    {
        $queryServer = new QueryServer(OrmEntity::createEntity([]));
        $this->assertNull($queryServer->getQueryPagination());
    }

    public function testParser()
    {
        $url = "https://www.baidu.com?page=1&page_size=15";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));
        //验证表达式
        $this->assertEquals([
            'page' => 1,
            'page_size' => 15,
        ], $queryServer->getQueryPagination()->toArray());
    }

    public function testParserPageSize()
    {
        $url = "https://www.baidu.com?page=1&page_size=50";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));
        //验证表达式
        $this->assertEquals([
            'page' => 1,
            'page_size' => 50,
        ], $queryServer->getQueryPagination()->toArray());
    }

    public function testParserPage()
    {
        $url = "https://www.baidu.com?page=19&page_size=50";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));
        //验证表达式
        $this->assertEquals([
            'page' => 19,
            'page_size' => 50,
        ], $queryServer->getQueryPagination()->toArray());
    }
}
