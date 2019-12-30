<?php

namespace MakeModel\Definition\Template;

class Getter
{

    private $field;
    private $returnValueType;

    /**
     * Getter constructor.
     * @param string $field
     * @param string $returnValueType
     */
    public function __construct($field, $returnValueType)
    {
        $this->field = (string)$field;
        $this->returnValueType = (string)$returnValueType;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getReturnValueType()
    {
        return $this->returnValueType;
    }

}