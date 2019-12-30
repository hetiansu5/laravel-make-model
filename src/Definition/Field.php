<?php

namespace MakeModel\Definition;

use MakeModel\Definition\DataType\ArrayType;
use MakeModel\Definition\DataType\FloatType;
use MakeModel\Definition\DataType\IntType;
use MakeModel\Definition\DataType\StringType;
use MakeModel\Definition\DataType\TimestampType;
use MakeModel\Definition\DataType\TypeInterface;

class Field
{
    /**
     * @var FieldInput
     */
    private $fieldInput;

    /**
     * @var TypeInterface
     */
    private $type;

    public function __construct(FieldInput $fieldInput)
    {
        $this->fieldInput = $fieldInput;
    }

    public function isAutoIncrement()
    {
        return $this->fieldInput->getExtra()->contain('auto_increment');
    }

    public function isPrimary()
    {
        return $this->fieldInput->getKey()->equal('PRI');
    }

    public function isNullable()
    {
        return $this->fieldInput->getNull()->equal('YES');
    }

    public function getOriginType()
    {
        return $this->fieldInput->getType();
    }

    /**
     * @return TypeInterface
     */
    public function getType()
    {
        if ($this->type === null) {
            $this->judgeType();
        }
        return $this->type;
    }

    public function getField()
    {
        return $this->fieldInput->getField();
    }

    public function getDefault()
    {
        return $this->fieldInput->getDefault();
    }

    public function getKey()
    {
        return $this->fieldInput->getKey();
    }

    public function getExtra()
    {
        return $this->fieldInput->getExtra();
    }

    private function judgeType()
    {
        $originType = $this->fieldInput->getType();
        if ($originType->prefix(['int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'numeric'])) {
            $this->type = IntType::getInstance();
        } else if ($originType->prefix(['float', 'double', 'decimal'])) {
            $this->type = FloatType::getInstance();
        } else if ($originType->prefix(['timestamp', 'time', 'date'])) {
            $this->type = TimestampType::getInstance();
        } else if ($originType->prefix(['json'])) {
            $this->type = ArrayType::getInstance();
        } else {
            $this->type = StringType::getInstance();
        }
    }

}