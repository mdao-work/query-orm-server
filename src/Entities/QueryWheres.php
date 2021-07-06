<?php


namespace mdao\QueryOrmServer\Entities;

use Exception;
use InvalidArgumentException;
use Traversable;

class QueryWheres implements \JsonSerializable, \ArrayAccess, \Iterator, \Countable
{
    // 数据信息
    protected $data = [];
    // 当前类名称
    protected $class;


    /**
     * 构造方法
     * @access public
     * @param array|object $data 数据
     */
    public function __construct(array $data = [])
    {
        $this->filters($data);
        $this->class = static::class;
    }

    /**
     * @param $field
     * @param null $value
     * @return $this
     */
    public function setFilter($field, $value = null): self
    {
        $this->data[$field] = $value;
        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function filters(array $items = []): self
    {
        foreach ($items as $value) {
            list($field, $operator, $value) = $value;
            $this->data[$field] = new QueryWhere($field, $operator, $value);
        }
        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public static function createFilters(array $items = []): self
    {
        return new static($items);
    }

    /**
     * 获取对象原始数据 如果不存在指定字段返回false
     * @access public
     * @param string $name 字段名 留空获取全部
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getData($name = null): ?QueryWhere
    {
        if (is_null($name)) {
            return $this->data;
        } elseif (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            return null;
        }
    }

    /**
     * 转换当前模型对象为数组
     * @access public
     * @return array
     */
    public function toArray()
    {
        if (empty($this->data)) {
            return [];
        }

        $list = [];

        /**
         * @var $queryWhere QueryWhere
         */
        foreach ($this->data as $queryWhere) {
            $list[$queryWhere->getField()] = [
                'field' => $queryWhere->getField(),
                'operator' => $queryWhere->getOperator(),
                'value' => $queryWhere->getValue(),
            ];
        }
        return $list;
    }

    /**
     * 转换当前模型对象为JSON字符串
     * @access public
     * @param integer $options json参数
     * @return string
     */
    public function toJson($options = JSON_UNESCAPED_UNICODE)
    {
        return json_encode(array_values($this->toArray()), $options);
    }

    /**
     * 修改器 设置数据对象的值
     * @access public
     * @param string $name 名称
     * @param mixed $value 值
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setFilter($name, $value);
    }

    /**
     * 获取器 获取数据对象的值
     * @access public
     * @param string $name 名称
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->getData($name);
    }

    /**
     * 检测数据对象的值
     * @access public
     * @param string $name 名称
     * @return boolean
     */
    public function __isset($name)
    {
        try {
            if (array_key_exists($name, $this->data)) {
                return true;
            } else {
                $this->getData($name);
                return true;
            }
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * 销毁数据对象的值
     * @access public
     * @param string $name 名称
     * @return void
     */
    public function __unset(string $name)
    {
        unset($this->data[$name]);
    }

    public function __toString()
    {
        return $this->toJson();
    }

    // JsonSerializable
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    // ArrayAccess
    public function offsetSet($name, $value)
    {
        $this->setFilter($name, $value);
    }

    public function offsetExists($name): bool
    {
        return $this->__isset($name);
    }

    public function offsetUnset($name)
    {
        $this->__unset($name);
    }

    public function offsetGet($name)
    {
        if (is_numeric($name)) {
            $data = array_values($this->data);
            return !isset($data[$name]) ? null : $data[$name];
        }
        return $this->getData($name);
    }

    public function count()
    {
        return count($this->data);
    }

    public function rewind()
    {
        reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function next()
    {
        return next($this->data);
    }

    public function valid()
    {
        return ($this->current() !== false);
    }
}
