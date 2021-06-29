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
            foreach ($this->param as $key => $value) {

                if ($key == 'filter') {
                    $this->filter = $value;
                }

                if ($key == 'order_by') {
                    $this->orderBy = $value;
                }
                if ($key == 'sorted_by') {
                    $this->sortedBy = $value;
                }
                if ($key == 'page') {
                    $this->page = $value;
                }

                if ($key == 'page_size') {
                    $this->pageSize = $value;
                }

                if ($key == 'select') {
                    $this->select = $value;
                }

                if ($key == 'where_or') {
                    $this->whereOr = $value;
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
     * @return string
     */
    public function getFilter(): string
    {
        return $this->filter;
    }

    /**
     * @return string
     */
    public function getWhereOr(): string
    {
        return $this->whereOr;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @return string
     */
    public function getSortedBy(): string
    {
        return $this->sortedBy;
    }

    /**
     * @return string
     */
    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getPageSize(): string
    {
        return $this->pageSize;
    }

    /**
     * @return string
     */
    public function getSelect(): string
    {
        return $this->select;
    }

}
