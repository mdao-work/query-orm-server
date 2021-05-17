<?php


namespace mdao\QueryOrm\Entities;

use mdao\QueryOrm\Contracts\Arrayable;

class QueryFilter implements Arrayable
{
    /**
     * @var string
     */
    protected $field;
    /**
     * @var string
     */
    protected $operator;

    /**
     * @var array|int|string
     */
    protected $value;

    /**
     * QueryFilter constructor.
     * @param string $field
     * @param string $operator
     * @param string|array|int $value
     */
    public function __construct(string $field, string $operator, $value)
    {
        $this->setField($field);
        $this->setOperator($operator);
        $this->setValue($value);
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator(string $operator): void
    {
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        if (is_array($this->value)) {
            return empty($this->value) ? '' : implode(',', $this->value);
        }
        return $this->value;
    }


    /**
     * @param array|int|string $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     *
     */
    public function parserOperator()
    {
        switch ($this->getOperator()) {
            case "in":
                $operator = $this->getField() . '[in]';
                break;
            case "like":
                $operator = $this->getField() . '[~]';
                break;
            case "<>":
                $operator = $this->getField() . '[!]';
                break;
            case "<":
                $operator = $this->getField() . '[<]';
                break;
            case "<=":
                $operator = $this->getField() . '[<=]';
                break;
            case ">":
                $operator = $this->getField() . '[>]';
                break;
            case ">=":
                $operator = $this->getField() . '[>=]';
                break;
            case "between":
                $operator = $this->getField() . '[<>]';
                break;
            case "not between":
                $operator = $this->getField() . '[><]';
                break;
            case "=":
            default:
                $operator = $this->getField() . '[=]';
                break;
        }
        return $operator;
    }

    /**
     * @return array
     */
    public function valueToArray(): array
    {
        return is_array($this->value) ? $this->value : explode(',', $this->value);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            $this->getField(),
            $this->getOperator(),
            $this->getValue(),
        ];
    }
}
