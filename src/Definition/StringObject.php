<?php

namespace MakeModel\Definition;

class StringObject
{
    private $s;

    public function __construct($input)
    {
        $this->s = (string)$input;
    }

    /**
     * 包含
     *
     * @param $subString
     * @return bool
     */
    public function contain($subString)
    {
        return strpos($this->s, $subString) !== false;
    }

    /**
     * 前缀子串
     *
     * @param array|string $subString
     * @return bool
     */
    public function prefix($subString)
    {
        if (is_array($subString)) {
            foreach ($subString as $sub) {
                if ($this->prefix($sub)) {
                    return true;
                }
            }
            return false;
        }
        $len = strlen($subString);
        return substr($this->s, 0, $len) === $subString;
    }

    /**
     * @param $string
     * @return bool
     */
    public function equal($string)
    {
        return $this->s === $string;
    }

    /**
     * 两边加引号
     *
     * @return string
     */
    public function quote()
    {
        return "'{$this->s}'";
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->__toString();
    }

    public function __toString()
    {
        return $this->s;
    }

}