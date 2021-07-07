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
}