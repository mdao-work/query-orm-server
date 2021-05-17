<?php


namespace mdao\QueryOrm\Contracts;

use mdao\QueryOrm\Entities\QueryPagination;
use mdao\QueryOrm\Entities\QuerySelect;

interface ParserEntityContract
{
    public function apply($param);
}
