<?php

use mdao\QueryOrm\Parser;
use mdao\QueryOrm\Servers\QueryServer;
use mdao\QueryOrm\Entities\OrmEntity;

require_once './vendor/autoload.php';

$data = [
    'filter' => [
        // 'created_at' => '1',
        // 'created_at_1' => ['1', 2],
        'created_at_3[>]' => 2,
        'created_at[in]' => '1',
        'created_at[~]' => '1',
        'created_at[!]' => '1',
    ],
    'order_by' => 'a',
    'sorted_by' => 'ase',
    'page' => 1,
    'select' => 'a,b,d',
    // 'page_size' => 15,
];

// dump(http_build_query($data));die;
// createEntity
$a = new QueryServer(OrmEntity::createEntity($data));
dump($a->getQueryFilter()[0]->getField());
dump($a->getQueryFilter()[0]->getOperator());
dump($a->getQueryFilter()[0]->getValue());
// dump($a->getQueryFilter()->toArray());

// dump($a->getQueryOrderBy());
// dump($a->getQueryPagination());
// dd($a->getQuerySelect());
die;
// $parser = new Parser();

// //url 模式
// $parserUrlEntity = (new mdao\QueryOrm\Entities\ParserUrlEntity());
// $url = 'www.xx.com?filter%5Bcreated_at%5B%3D%5D%5D=1&filter%5Bcreated_at_1%5Bin%5D%5D=1%2C2&filter%5Bcreated_at%5Bin%5D%5D=1&filter%5Bcreated_at%5B%7E%5D%5D=1&filter%5Bcreated_at%5B%21%5D%5D=1&filter%5Bcreated_at%5B%3E%5D%5D=1&filter%5Bcreated_at%5B%3E%3D%5D%5D=1&filter%5Bcreated_at%5B%3C%5D%5D=1&filter%5Bcreated_at%5B%3C%3D%5D%5D=1&filter%5Bcreated_at%5B%3C%3E%5D%5D=1&filter%5Bcreated_at%5B%3E%3C%5D%5D=1&select=a%2Cb&order_by=a&sorted_by=ase&page=1&page_size=15';
// dump($parser->apply($parserUrlEntity, $url)->toUriQueryString());
// dump($parser->apply($parserUrlEntity, $url)->toArray());
// dump($parser->apply($parserUrlEntity, $url)->toJson());
//
// $parserDataEntity = (new mdao\QueryOrm\Entities\ParserDataEntity());
//
// //data 模式
// $data = [
//     'filter' => [
//         'created_at' => '1',
//         'created_at_1' => ['1', 2],
//         'created_at[in]' => '1',
//         'created_at[~]' => '1',
//         'created_at[!]' => '1',
//     ],
//     'order_by' => 'a',
//     'sorted_by' => 'ase',
//     'page' => 1,
//     'page_size' => 15,
//     'select' => 'a,b',
// ];
//
// dump($parser->apply($parserDataEntity, $data)->toUriQueryString());
// dump($parser->apply($parserDataEntity, $data)->toArray());
// dump($parser->apply($parserDataEntity, $data)->toJson());


$b = (new mdao\QueryOrm\QueryClient())
    ->where('a', '=', 'b')
    ->whereIn('da', 'ddb')
    ->select(['b', 'd', 'ded', 'd'])
    ->whereBetween('b', 'd')
    ->orderBy('d', 'desc')
    ->page(1, 15)
    ->toArray();

dd($b);
die;
die;
