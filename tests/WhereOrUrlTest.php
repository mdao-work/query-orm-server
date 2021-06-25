<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereOrUrlTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserEmpty()
    {
        $url = http_build_query(['where_or' => []]);

        $queryServer = new QueryServer(OrmEntity::createEntity($url));
        $this->assertNull($queryServer->getQueryWhereOrs());
    }

    /**
     * 表达式
     * @throws ParserException
     */
    public function testParserExp()
    {
        $url = "https://www.baidu.com?where_or[field_1{eq}]=1&where_or[field_2{neq}]=2&where_or[field_3{gt}]=3&where_or[field_4{egt}]=4&where_or[field_5{lt}]=5&where_or[field_6{elt}]=6&where_or[field_7{like}]=7&where_or[field_8{in}]=8&where_or[field_9{between}]=9&where_or[field_10]=10";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

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
     * in表达式
     * @throws ParserException
     */
    public function testParserInExp()
    {
        $url = "https://www.baidu.com?where_or[field_1]=[1,2,3]&where_or[field_2]=2,3,4&where_or[field_3{in}]=3&where_or[field_4{in}]=2,3,4&where_or[field_5{in}]=[1,2,3]";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        //验证字段
        $this->assertEquals('field_1', $queryServer->getQueryWhereOrs()[0]->toArray()[0]);
        $this->assertEquals('field_2', $queryServer->getQueryWhereOrs()[1]->toArray()[0]);
        $this->assertEquals('field_3', $queryServer->getQueryWhereOrs()[2]->toArray()[0]);
        $this->assertEquals('field_4', $queryServer->getQueryWhereOrs()[3]->toArray()[0]);
        $this->assertEquals('field_5', $queryServer->getQueryWhereOrs()[4]->toArray()[0]);

        //验证表达式
        $this->assertEquals('in', $queryServer->getQueryWhereOrs()[0]->toArray()[1]);
        $this->assertEquals('=', $queryServer->getQueryWhereOrs()[1]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWhereOrs()[2]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWhereOrs()[3]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWhereOrs()[4]->toArray()[1]);

        //验证值
        $this->assertEquals('1,2,3', $queryServer->getQueryWhereOrs()[0]->toArray()[2]);
        $this->assertEquals('2,3,4', $queryServer->getQueryWhereOrs()[1]->toArray()[2]);
        $this->assertEquals('3', $queryServer->getQueryWhereOrs()[2]->toArray()[2]);
        $this->assertEquals('2,3,4', $queryServer->getQueryWhereOrs()[3]->toArray()[2]);
        $this->assertEquals('1,2,3', $queryServer->getQueryWhereOrs()[4]->toArray()[2]);
    }

    /**
     * 字段
     * @throws ParserException
     */
    public function testParserField()
    {
        $url = "https://www.baidu.com?field_1[eq]=1&field_2[neq]=2&field_3[gt]=3&field_4[egt]=4&field_5[lt]=5&field_6[elt]=6&field_7[like]=7&field_8[in]=8&field_9[between]=9&field_10=10";
        //1.0 用parse_url解析URL
        $data = parse_url($url);

        //2.0 将URL中的参数取出来放到数组里
        $arrQuery = $this->convertUrlQuery($data['query']);

        $queryServer = new QueryServer(OrmEntity::createEntity(['where_or' => $arrQuery]));

        //验证表达式
        $this->assertEquals('field_1', $queryServer->getQueryWhereOrs()[0]->toArray()[0]);
        $this->assertEquals('field_2', $queryServer->getQueryWhereOrs()[1]->toArray()[0]);
        $this->assertEquals('field_3', $queryServer->getQueryWhereOrs()[2]->toArray()[0]);
        $this->assertEquals('field_4', $queryServer->getQueryWhereOrs()[3]->toArray()[0]);
        $this->assertEquals('field_5', $queryServer->getQueryWhereOrs()[4]->toArray()[0]);
        $this->assertEquals('field_6', $queryServer->getQueryWhereOrs()[5]->toArray()[0]);
        $this->assertEquals('field_7', $queryServer->getQueryWhereOrs()[6]->toArray()[0]);
        $this->assertEquals('field_8', $queryServer->getQueryWhereOrs()[7]->toArray()[0]);
        $this->assertEquals('field_9', $queryServer->getQueryWhereOrs()[8]->toArray()[0]);
        $this->assertEquals('field_10', $queryServer->getQueryWhereOrs()[9]->toArray()[0]);
    }

    /**
     * 值
     * @throws ParserException
     */
    public function testParserValue()
    {
        $url = "https://www.baidu.com?where_or[field_1{eq}]=1&where_or[field_2{neq}]=2&where_or[field_3{gt}]=3&where_or[field_4{egt}]=4&where_or[field_5{lt}]=5&where_or[field_6{elt}]=6&where_or[field_7{like}]=7&where_or[field_8{in}]=8,9&where_or[field_9{between}]=9,10&where_or[field_10]=10";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));
        //验证表达式
        $this->assertEquals('1', $queryServer->getQueryWhereOrs()[0]->toArray()[2]);
        $this->assertEquals('2', $queryServer->getQueryWhereOrs()[1]->toArray()[2]);
        $this->assertEquals('3', $queryServer->getQueryWhereOrs()[2]->toArray()[2]);
        $this->assertEquals('4', $queryServer->getQueryWhereOrs()[3]->toArray()[2]);
        $this->assertEquals('5', $queryServer->getQueryWhereOrs()[4]->toArray()[2]);
        $this->assertEquals('6', $queryServer->getQueryWhereOrs()[5]->toArray()[2]);
        $this->assertEquals('7', $queryServer->getQueryWhereOrs()[6]->toArray()[2]);
        $this->assertEquals('8,9', $queryServer->getQueryWhereOrs()[7]->toArray()[2]);
        $this->assertEquals('9,10', $queryServer->getQueryWhereOrs()[8]->toArray()[2]);
        $this->assertEquals('10', $queryServer->getQueryWhereOrs()[9]->toArray()[2]);
    }

    /**
     * Returns the url query as associative array
     *
     * @param string    query
     * @return    array    params
     */
    private function convertUrlQuery($query)
    {
        $queryParts = explode('&', $query);

        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }

        return $params;
    }
}
