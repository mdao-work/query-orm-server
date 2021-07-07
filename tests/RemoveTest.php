<?php


namespace mdao\QueryOrmServer\Test;


use mdao\QueryOrmServer\Entities\OrmEntity;
use mdao\QueryOrmServer\Exception\ParserException;
use mdao\QueryOrmServer\Servers\QueryServer;
use PHPUnit\Framework\TestCase;

class RemoveTest extends TestCase
{
    /**
     * 测试移除条件
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testRemoveWhereField()
    {
        $data = [
            'filter' => [
                'field_1{eq}' => '=',
            ],
        ];

        //移除一个条件
        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        $queryServer->removeWhere('field_1');
        $this->assertEquals([], $queryServer->getQueryWheres()->toArray());

        $data2 = [
            'filter' => [
                'field_1{eq}' => '=',
                'field_2{eq}' => '=',
            ],
        ];
        //移除多个条件
        $queryServer2 = new QueryServer(OrmEntity::createEntity($data2));
        $queryServer2->removeWhere('field_1', 'field_2');
        $this->assertEquals([], $queryServer2->getQueryWheres()->toArray());


        $data3 = [
            'filter' => [
                'field_1{eq}' => '=',
                'field_2{eq}' => '=',
                'field_3{eq}' => '=',
            ],
        ];
        //移除中间一个条件
        $queryServer3 = new QueryServer(OrmEntity::createEntity($data3));
        $queryServer3->removeWhere('field_2');
        $this->assertEquals([
            "field_1" => [
                "field" => "field_1",
                "operator" => "=",
                "value" => "=",
            ],
            "field_3" => [
                "field" => "field_3",
                "operator" => "=",
                "value" => "=",
            ]
        ], $queryServer3->getQueryWheres()->toArray());
        $this->assertCount(2, $queryServer3->getQueryWheres()->toArray());


        $data5 = [
            'filter' => [
                'field_1{eq}' => '=',
                'field_2{eq}' => '=',
                'field_3{eq}' => '=',
            ],
        ];
        //移除所有条件后，然后新增条件
        $queryServer5 = new QueryServer(OrmEntity::createEntity($data5));
        $queryServer5->removeWhere('field_1');
        $queryServer5->removeWhere('field_2');
        $queryServer5->removeWhere('field_3');
        $this->assertEquals([], $queryServer5->getQueryWheres()->toArray());

        //新增一个条件
        $queryServer5->where('field_5', 18);

        $this->assertEquals([
            "field_5" => [
                "field" => "field_5",
                "operator" => "=",
                "value" => "18",
            ]
        ], $queryServer5->getQueryWheres()->toArray());
        $this->assertCount(1, $queryServer5->getQueryWheres()->toArray());

        //新增第二个条件
        $queryServer5->where('field_6', 18);

        $this->assertEquals([
            "field_5" => [
                "field" => "field_5",
                "operator" => "=",
                "value" => "18",
            ],
            "field_6" => [
                "field" => "field_6",
                "operator" => "=",
                "value" => "18",
            ]
        ], $queryServer5->getQueryWheres()->toArray());

        $this->assertCount(2, $queryServer5->getQueryWheres()->toArray());

        //然后在移除一个条件
        $queryServer5->removeWhere('field_5');

        $this->assertEquals([
            "field_6" => [
                "field" => "field_6",
                "operator" => "=",
                "value" => "18",
            ]
        ], $queryServer5->getQueryWheres()->toArray());
        $this->assertCount(1, $queryServer5->getQueryWheres()->toArray());
    }

    /**
     * 测试移除条件
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testRemoveWhereFields()
    {
        $data = [
            'filter' => [
                'field_1{eq}' => '=',
            ],
        ];

        //移除一个条件
        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        $queryServer->removeWhere('field_1');
        $this->assertEquals([], $queryServer->getQueryWheres()->toArray());

        $data2 = [
            'filter' => [
                'field_1{eq}' => '=',
                'field_2{eq}' => '=',
            ],
        ];
        //移除多个条件
        $queryServer2 = new QueryServer(OrmEntity::createEntity($data2));
        $queryServer2->removeWhere('field_1', 'field_2');
        $this->assertEquals([], $queryServer2->getQueryWheres()->toArray());

        $data3 = [
            'filter' => [
                'field_1{eq}' => '=',
                'field_2{eq}' => '=',
            ],
        ];
        //移除中间一个条件
        $queryServer3 = new QueryServer(OrmEntity::createEntity($data3));
        $queryServer3->removeWhere(['field_1', 'field_2']);
        $this->assertEquals([], $queryServer3->getQueryWheres()->toArray());

    }

    /**
     * 测试order条件
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testRemoveOrder()
    {
        //url?order_by=id,type&sorted_by=desc,asc
        $url = "https://www.baidu.com?order_by=field_1&sorted_by=desc";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        //移除一个条件
        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));
        $queryServer->removeOrder('field_1');
        $this->assertEquals([], $queryServer->getQueryOrderBy()->toArray());


        //url?order_by=id,type&sorted_by=desc,asc
        $url = "https://www.baidu.com?order_by=field_1,field_2&sorted_by=desc,desc";
        //1.0 用parse_url解析URL
        $data2 = parse_url($url);
        parse_str($data2['query'], $arrQuery2);

        //移除多个条件
        $queryServer2 = new QueryServer(OrmEntity::createEntity($arrQuery2));
        $queryServer2->removeOrder('field_1', 'field_2');
        $this->assertEquals([], $queryServer2->getQueryOrderBy()->toArray());

        //url?order_by=id,type&sorted_by=desc,asc
        $url = "https://www.baidu.com?order_by=field_1,field_2&sorted_by=desc,desc";
        //1.0 用parse_url解析URL
        $data5 = parse_url($url);
        parse_str($data5['query'], $arrQuery5);

        //移除所有条件后，然后新增条件
        $queryServer5 = new QueryServer(OrmEntity::createEntity($arrQuery5));
        $queryServer5->removeOrder('field_1');
        $queryServer5->removeOrder('field_2');
        $this->assertEquals([], $queryServer5->getQueryOrderBy()->toArray());

        //新增一个
        $queryServer5->orderBy('field_5', 'desc');

        $this->assertEquals([
            [
                "field_5",
                "desc"
            ]
        ], $queryServer5->getQueryOrderBy()->toArray());
        $this->assertCount(1, $queryServer5->getQueryOrderBy()->toArray());

        //新增第二个条件
        $queryServer5->orderBy('field_6', 'asc');

        $this->assertEquals([
            [
                "field_5",
                "desc"
            ],
            [
                "field_6",
                "asc"
            ],
        ], $queryServer5->getQueryOrderBy()->toArray());

        $this->assertCount(2, $queryServer5->getQueryOrderBy()->toArray());

        //然后在移除一个条件
        $queryServer5->removeOrder('field_5');

        $this->assertEquals([
            [
                "field_6",
                "asc"
            ]
        ], $queryServer5->getQueryOrderBy()->toArray());
        $this->assertCount(1, $queryServer5->getQueryOrderBy()->toArray());
    }

    /**
     * 测试移除select
     * @throws \mdao\QueryOrmServer\Exception\ParserException
     */
    public function testRemoveSelect()
    {
        $url = "https://www.baidu.com?select=,id,date,content:text,aa:b,";
        //1.0 用parse_url解析URL
        $data = parse_url($url);
        parse_str($data['query'], $arrQuery);

        $queryServer = new QueryServer(OrmEntity::createEntity($arrQuery));
        $queryServer->removeSelect(['id']);
        $queryServer->removeSelect(['content']);
        $queryServer->removeSelect(['aa']);
        $this->assertEquals(null, $queryServer->getQueryOrderBy());
    }

}