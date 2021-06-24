<?php


namespace mdao\QueryOrmServer\Entities;

use mdao\QueryOrmServer\Contracts\ParserEntityContract;

class ParserDataEntity implements ParserEntityContract
{
    /**
     * @param array $param
     * @return array
     */
    public function apply($param)
    {
        return $param;
    }
}
