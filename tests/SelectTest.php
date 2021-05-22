<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class SelectTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserEmpty()
    {
        $queryServer = new QueryServer(OrmEntity::createEntity([]));
        $this->assertNull($queryServer->getQuerySelect());
    }

    /**
     * @throws ParserException
     */
    public function testParser()
    {
        $url = "https://www.baidu.com?select=id,date,content";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        //验证表达式
        $this->assertEquals(['id', 'date', 'content'], $queryServer->getQuerySelect()->toArray());
    }

    /**
     * 字段
     * @throws ParserException
     */
    public function testParserNull()
    {
        $url = "https://www.baidu.com?select=";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        //验证表达式
        $this->assertEquals(null, $queryServer->getQuerySelect());
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserAlias()
    {
        $url = "https://www.baidu.com?select=id,date,content:text,aa:b";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        //验证表达式
        $this->assertEquals(['id', 'date', 'text', 'b'], $queryServer->getQuerySelect()->toArray());
        $this->assertEquals(['content' => 'text', 'aa' => 'b'], $queryServer->getQuerySelect()->getAlias());
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserTrim()
    {
        $url = "https://www.baidu.com?select=,id,date,content:text,aa:b,";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        //验证表达式
        $this->assertEquals(['id', 'date', 'text', 'b'], $queryServer->getQuerySelect()->toArray());
        $this->assertEquals(['content' => 'text', 'aa' => 'b'], $queryServer->getQuerySelect()->getAlias());
    }
}
