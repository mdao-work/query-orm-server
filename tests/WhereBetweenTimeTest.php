<?php

namespace mdao\QueryOrmServer\Test;

use mdao\QueryOrmServer\Exception\ParserException;
use \PHPUnit\Framework\TestCase;
use mdao\QueryOrmServer\Servers\QueryServer;
use mdao\QueryOrmServer\Entities\OrmEntity;

class WhereBetweenTimeTest extends TestCase
{
    /**
     * @throws ParserException
     */
    public function testParserEmpty()
    {
        $queryServer =QueryServer::create();
        $queryServer->whereBetweenTime('s',1624513922);
        $this->assertEquals('between', $queryServer->getQueryWheres()[0]->toArray()[1]);
        $this->assertEquals([1624513922,1624600322], $queryServer->getQueryWheres()[0]->valueToArray());
    }
}
