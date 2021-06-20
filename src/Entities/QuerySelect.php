<?php


namespace mdao\QueryOrmServer\Entities;

use mdao\QueryOrmServer\Contracts\Arrayable;

class QuerySelect implements Arrayable
{
    /**
     * 查询字段
     * @var array
     */
    protected $select = [];

    /**
     * @var array
     */
    private $alias = [];

    public function __construct(array $select = [])
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
        $select = $this->select;
        if (empty($select)) {
            return [];
        }

        if (!$alias = $this->getAlias()) {
            return $select;
        }

        //存在别名，则输出别名
        foreach ($select as $index => $item) {
            if ($result = $this->parserAlias($item)) {
                if (isset($alias[$result[0]])) {
                    $select[$index] = $result[0] . ' as ' . $alias[$result[0]];
                }
            }
        }

        return $select;
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

    /**
     * @return string
     */
    public function getSelectToString(): ?string
    {
        $select = $this->getSelect();
        return empty($select) ? null : implode(',', $select);
    }

    /**
     * 获取所有查询的真实的key
     * @return array|null
     */
    public function getKeys(): ?array
    {
        $select = $this->select;
        if (empty($select)) {
            return null;
        }

        if (!$alias = $this->getAlias()) {
            return $select;
        }

        //存在别名，则输出别名
        foreach ($select as $index => $item) {
            if ($result = $this->parserAlias($item)) {
                if (isset($alias[$result[0]])) {
                    $select[$index] = $result[0];
                }
            }
        }

        return empty($select) ? null : $select;
    }
}
