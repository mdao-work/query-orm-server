<?php


namespace mdao\QueryOrmServer\Test;


use mdao\QueryOrmServer\Entities\OrmEntity;
use mdao\QueryOrmServer\Exception\ParserException;
use mdao\QueryOrmServer\Servers\QueryServer;
use PHPUnit\Framework\TestCase;

class RemoveTest  extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testRemoveWhereField()
    {
        $data = [
            'filter' => [
                'field_1{eq}' => '=', //等于
                'field_2{neq}' => '<>',//不等于
            ],
        ];

        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        $this->assertEquals(null,$queryServer->removeWhere('field_1','field_2'));
//        $this->assertEquals(true, $queryServer->hasWhereField('field_2'));
//        $this->assertEquals(true, $queryServer->hasWhereField(['field_1', 'field_2']));
//        $this->assertEquals(true, $queryServer->hasWhereField('field_1', 'field_2'));
//        $this->assertEquals(false, $queryServer->hasWhereField('field_3'));
//        $this->assertEquals(false, $queryServer->hasWhereField(['field_1', 'field_3']));
//        $this->assertEquals(false, $queryServer->hasWhereField('field_1', 'field_3'));
//        $this->assertEquals(false, $queryServer->hasWhereField(['field_3', 'field_5']));
    }
}