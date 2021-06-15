<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereArrayByAddTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserAdd()
    {
        $data = [
            'filter' => [],
        ];
        $queryServer = new QueryServer(OrmEntity::createEntity($data));
        $queryServer->where('a', '=', '1');
        $queryServer->where('c', '=', '3');
        $queryServer->where('5', '=', '3');
        $queryServer->where('6', '=', '3');
        $queryServer->where('7', '=', '3');
        $queryServer->removeWhere('a');
        dd($queryServer->getQueryWheres());
        $this->assertNull($queryServer->getQueryWheres());
    }


}
