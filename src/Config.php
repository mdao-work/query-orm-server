<?php


namespace mdao\QueryOrmServer;


/**
 * todo config 文件暂时简单处理，后续优化
 * Class Config
 * @package mdao\QueryOrmServer
 */
class Config
{
    /**
     * @var string
     */
    protected $filter;
    /**
     * @var string
     */
    protected $whereOr;
    /**
     * @var string
     */
    protected $orderBy;
    /**
     * @var string
     */
    protected $sortedBy;
    /**
     * @var string
     */
    protected $page;
    /**
     * @var string
     */
    protected $pageSize;
    /**
     * @var string
     */
    protected $select;

    /**
     * @var array|string[]
     */
    protected $default = [
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
            $this->default = array_merge($this->default, $param['param']);
        }

        // 比较笨的办法
        foreach ($this->default as $key => $value) {
            switch ($key) {
                case 'filter':
                    $this->filter = $value;
                    break;
                case 'order_by':
                    $this->orderBy = $value;
                    break;
                case 'sorted_by':
                    $this->sortedBy = $value;
                    break;
                case 'page':
                    $this->page = $value;
                    break;
                case 'page_size':
                    $this->pageSize = $value;
                    break;
                case 'select':
                    $this->select = $value;
                    break;
                case 'where_or':
                    $this->whereOr = $value;
                    break;
            }
        }
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
