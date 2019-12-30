<?php

namespace MakeModel\Definition;

use Illuminate\Database\Eloquent\Collection;

class Table extends Collection
{

    public function __construct(array $tableDesc)
    {
        $items = [];
        foreach ($tableDesc as $fieldDesc) {
            $items[] = new Field(new FieldInput($fieldDesc));
        }
        parent::__construct($items);
    }

}