<?php

namespace MakeModel\Definition\DataType;

interface TypeInterface
{

    /**
     * @return int
     */
    public function getType();

    /**
     * @return string
     */
    public function toString();

}