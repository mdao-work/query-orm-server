<?php


namespace mdao\QueryOrm\Entities;

use mdao\QueryOrm\Contracts\ParserEntityContract;

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
