<?php


namespace mdao\QueryOrm\Entities;

use mdao\QueryOrm\Contracts\Arrayable;

class QuerySelect implements Arrayable
{
    /**
     * 查询字段
     * @var array
     */
    protected $select = ['*'];

    /**
     * @var array
     */
    private $alias = [];

    public function __construct(array $select = ['*'])
    {
        $this->setSelect($select);
        foreach ($select as $index => $item) {
            if ($result = $this->parserAlias($item)) {
                $this->setAlias($result[0], $result[1]);
            }
        }
    }

    /**
     * @return array
     */
    public function getSelect(): array
    {
        if (empty($this->select)) {
            return [];
        }

        if (!$alias = $this->getAlias()) {
            return $this->select;
        }

        //存在别名，则输出别名
        foreach ($this->select as $index => $item) {
            if ($result = $this->parserAlias($item)) {
                if (isset($alias[$result[0]])) {
                    $this->select[$index] = $alias[$result[0]];
                }
            }
        }

        return $this->select;
    }

    /**
     * @param array $select
     */
    public function setSelect(array $select): void
    {
        $this->select = $select;
    }

    /**
     * 别名字段
     * @return array
     */
    public function getAlias(): array
    {
        return $this->alias;
    }

    /**
     * @param string $oriField
     * @param string $aliasField
     * @return $this
     */
    public function setAlias(string $oriField, string $aliasField): self
    {
        $this->alias[$oriField] = $aliasField;
        return $this;
    }

    /**
     * 解析别名字符串
     * @param string $field
     * @return array
     */
    private function parserAlias(string $field): array
    {
        if (stripos($field, ':')) {
            return explode(':', $field);
        }

        return [];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getSelect();
    }
}
