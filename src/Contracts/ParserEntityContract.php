<?php


namespace mdao\QueryOrmServer\Contracts;

use mdao\QueryOrmServer\Entities\QueryPagination;
use mdao\QueryOrmServer\Entities\QuerySelect;

interface ParserEntityContract
{
    public function apply($param);
}
