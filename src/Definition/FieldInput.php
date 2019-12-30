<?php

namespace MakeModel\Definition;

class FieldInput
{

    private $field;
    private $type;
    private $null;
    private $key;
    private $default;
    private $extra;

    public function __construct($fieldDesc)
    {
        $this->field = new StringObject($this->get($fieldDesc, 'Field'));
        $this->type = new StringObject($this->get($fieldDesc, 'Type'));
        $this->null = new StringObject($this->get($fieldDesc, 'Null'));
        $this->key = new StringObject($this->get($fieldDesc, 'Key'));
        $this->default = new StringObject($this->get($fieldDesc, 'Default'));
        $this->extra = new StringObject($this->get($fieldDesc, 'Extra'));
    }

    private function get($fieldDesc, $key)
    {
        return isset($fieldDesc->{$key}) ? $fieldDesc->{$key} : '';
    }

    /**
     * @return StringObject
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return StringObject
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return StringObject
     */
    public function getNull()
    {
        return $this->null;
    }

    /**
     * @return StringObject
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return StringObject
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return StringObject
     */
    public function getExtra()
    {
        return $this->extra;
    }

}