<?php


namespace mdao\QueryOrmServer;

use mdao\QueryOrmServer\Exception\ParserException;
use mdao\QueryOrmServer\Entities\ParserEntity;
use mdao\QueryOrmServer\Contracts\ParserEntityContract;

/**
 * todo config 文件暂时简单处理，后续优化
 * Class Config
 * @package mdao\QueryOrmServer
 */
class Config
{
    protected $filter = 'filter';
    protected $whereOr = 'where_or';
    protected $orderBy = 'order_by';
    protected $sortedBy = 'sorted_by';
    protected $page = 'page';
    protected $pageSize = 'page_size';
    protected $select = 'select';

    protected $param = [
        'filter' => 'filter',
        'where_or' => 'where_or',
        'order_by' => 'order_by',
        'sorted_by' => 'sorted_by',
        'page' => 'page',
        'page_size' => 'page_size',
        'select' => 'select',
    ];

    /**
     * Config constructor.
     * @param string[] $param
     */
    public function __construct(array $param = [])
    {
        if (!empty($param['param'])) {
            $this->param = array_merge($this->param, $param['param']);

            // 比较笨的办法
            foreach ($this->param as $attributes) {

                if (!empty($attributes['filter'])) {
                    $this->filter = $attributes['filter'];
                }

                if (!empty($attributes['order_by'])) {
                    $this->orderBy = $attributes['order_by'];
                }
                if (!empty($attributes['sorted_by'])) {
                    $this->sortedBy = $attributes['sorted_by'];
                }
                if (!empty($attributes['page'])) {
                    $this->page = $attributes['page'];
                }

                if (!empty($attributes['page_size'])) {
                    $this->pageSize = $attributes['page_size'];
                }

                if (!empty($attributes['select'])) {
                    $this->select = $attributes['select'];
                }

                if (!empty($attributes['where_or'])) {
                    $this->whereOr = $attributes['where_or'];
                }
            }
        }
    }

    /**
     * @return array|string[]
     */
    public function getParam(): array
    {
        return $this->param;
    }

    /**
     * @return mixed|string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return mixed|string
     */
    public function getWhereOr()
    {
        return $this->whereOr;
    }

    /**
     * @return mixed|string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return mixed|string
     */
    public function getSortedBy()
    {
        return $this->sortedBy;
    }

    /**
     * @return mixed|string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return mixed|string
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @return mixed|string
     */
    public function getSelect()
    {
        return $this->select;
    }

}
