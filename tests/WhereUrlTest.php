<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereUrlTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserEmpty()
    {
        $url = http_build_query(['filter' => []]);

        $queryServer = new QueryServer(OrmEntity::createEntity($url));
        $this->assertNull($queryServer->getQueryWheres());
    }

    /**
     * 表达式
     * @throws ParserException
     */
    public function testParserExp()
    {
        $url = "https://www.baidu.com?filter[field_1{eq}]=1&filter[field_2{neq}]=2&filter[field_3{gt}]=3&filter[field_4{egt}]=4&filter[field_5{lt}]=5&filter[field_6{elt}]=6&filter[field_7{like}]=7&filter[field_8{in}]=8&filter[field_9{between}]=9&filter[field_10]=10";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

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
     * in表达式
     * @throws ParserException
     */
    public function testParserInExp()
    {
        $url = "https://www.baidu.com?filter[field_1]=[1,2,3]&filter[field_2]=2,3,4&filter[field_3{in}]=3&filter[field_4{in}]=2,3,4&filter[field_5{in}]=[1,2,3]";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));

        //验证字段
        $this->assertEquals('field_1', $queryServer->getQueryWheres()[0]->toArray()[0]);
        $this->assertEquals('field_2', $queryServer->getQueryWheres()[1]->toArray()[0]);
        $this->assertEquals('field_3', $queryServer->getQueryWheres()[2]->toArray()[0]);
        $this->assertEquals('field_4', $queryServer->getQueryWheres()[3]->toArray()[0]);
        $this->assertEquals('field_5', $queryServer->getQueryWheres()[4]->toArray()[0]);

        //验证表达式
        $this->assertEquals('in', $queryServer->getQueryWheres()[0]->toArray()[1]);
        $this->assertEquals('=', $queryServer->getQueryWheres()[1]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWheres()[2]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWheres()[3]->toArray()[1]);
        $this->assertEquals('in', $queryServer->getQueryWheres()[4]->toArray()[1]);

        //验证值
        $this->assertEquals('1,2,3', $queryServer->getQueryWheres()[0]->toArray()[2]);
        $this->assertEquals('2,3,4', $queryServer->getQueryWheres()[1]->toArray()[2]);
        $this->assertEquals('3', $queryServer->getQueryWheres()[2]->toArray()[2]);
        $this->assertEquals('2,3,4', $queryServer->getQueryWheres()[3]->toArray()[2]);
        $this->assertEquals('1,2,3', $queryServer->getQueryWheres()[4]->toArray()[2]);
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

        $queryServer = new QueryServer(OrmEntity::createEntity(['filter' => $arrQuery]));

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
        $url = "https://www.baidu.com?filter[field_1{eq}]=1&filter[field_2{neq}]=2&filter[field_3{gt}]=3&filter[field_4{egt}]=4&filter[field_5{lt}]=5&filter[field_6{elt}]=6&filter[field_7{like}]=7&filter[field_8{in}]=8,9&filter[field_9{between}]=9,10&filter[field_10]=10";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));
        //验证表达式
        $this->assertEquals('1', $queryServer->getQueryWheres()[0]->toArray()[2]);
        $this->assertEquals('2', $queryServer->getQueryWheres()[1]->toArray()[2]);
        $this->assertEquals('3', $queryServer->getQueryWheres()[2]->toArray()[2]);
        $this->assertEquals('4', $queryServer->getQueryWheres()[3]->toArray()[2]);
        $this->assertEquals('5', $queryServer->getQueryWheres()[4]->toArray()[2]);
        $this->assertEquals('6', $queryServer->getQueryWheres()[5]->toArray()[2]);
        $this->assertEquals('7', $queryServer->getQueryWheres()[6]->toArray()[2]);
        $this->assertEquals('8,9', $queryServer->getQueryWheres()[7]->toArray()[2]);
        $this->assertEquals('9,10', $queryServer->getQueryWheres()[8]->toArray()[2]);
        $this->assertEquals('10', $queryServer->getQueryWheres()[9]->toArray()[2]);
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

    private function getUrlQuery($array_query)
    {
        $tmp = array();
        foreach ($array_query as $k => $param) {
            $tmp[] = $k . '=' . $param;
        }
        $params = implode('&', $tmp);
        return $params;
    }

    /**
     * 字符串、数组转换为格式化的数组
     * @param string $data 原始字符串
     * @return array
     */
    private function stringArrayConvertToArray(string $data): array
    {
        // 数组原样返回
        if (is_array($data)) {
            return $data;
        }
        $result = [];
        // 字符串处理
        $string = (string)$data;
        if (!empty($string) && preg_match('/^\[.*?]$/', $string)) {
            $result = json_decode($string, true);
        }

        if (!is_array($result) || count($result) < 1) {
            return [];
        }
        return $result;
    }
}
