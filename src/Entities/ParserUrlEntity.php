<?php


namespace mdao\QueryOrm\Entities;

use mdao\QueryOrm\Contracts\ParserEntityContract;

class ParserUrlEntity implements ParserEntityContract
{
    public function apply($url)
    {
        $result = parse_url($url);

        if (empty($result['query'])) {
            return [];
        }
        parse_str($result['query'], $result);
        return $result;
    }
}
