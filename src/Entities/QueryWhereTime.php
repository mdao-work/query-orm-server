<?php


namespace mdao\QueryOrmServer\Entities;

class QueryWhereTime extends QueryWhere
{
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
