<?php

namespace MakeModel\Definition\Template;

class Attribute
{
    const TYPE_PUBLIC = 'public';
    const TYPE_PROTECTED = 'protected';
    const TYPE_PRIVATE = 'private';

    private $type;
    private $name;
    private $value;

    /**
     * Attribute constructor.
     * @param string $type
     * @param string $name
     * @param null|string $value
     */
    public function __construct($type, $name, $value = null)
    {
        $this->type = (string)$type;
        $this->name = (string)$name;
        $this->value = $value !== null ? (string)$value : null;
    }

    /**
     * eg. public protected private
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}