<?php

namespace MakeModel\Definition\Template;

class ClassTemplate
{
    const SPACE = "    "; //缩进为4个空格

    private $namespace;

    private $uses = [];

    private $className;

    private $extend;

    private $implements = [];

    private $useTraits = [];

    /**
     * @var Attribute[]
     */
    private $attributes = [];

    /**
     * @var Getter[]
     */
    private $getters = [];

    /**
     * @var Getter[]
     */
    private $setters = [];

    /**
     * @var Getter[]
     */
    private $getSetters = [];

    public function setNameSpace($value)
    {
        $this->namespace = $value;
    }

    public function setClassName($value)
    {
        $this->className = $value;
    }

    public function setUses($value)
    {
        $this->uses = $value;
    }

    public function addUse($value)
    {
        $this->uses[] = $value;
    }

    public function setExtend($value)
    {
        $this->extend = $value;
    }

    public function setImplements($value)
    {
        $this->implements = $value;
    }

    public function addImplement($value)
    {
        $this->implements[] = $value;
    }

    public function setUseTraits($value)
    {
        $this->useTraits = $value;
    }

    public function addUseTraits($value)
    {
        $this->useTraits[] = $value;
    }

    public function setAttributes($value)
    {
        $this->attributes = $value;
    }

    public function addAttribute(Attribute $value)
    {
        $this->attributes[] = $value;
    }

    public function setGetters($value)
    {
        $this->getters = $value;
    }

    public function addGetter(Getter $value)
    {
        $this->getters[] = $value;
    }

    public function setSetters($value)
    {
        $this->setters = $value;
    }

    public function addSetter(Getter $value)
    {
        $this->setters[] = $value;
    }

    public function setGetSetters($value)
    {
        $this->getSetters = $value;
    }

    public function addGetSetters(Getter $value)
    {
        $this->getSetters[] = $value;
    }

    /**
     * @param null|string $path
     * @return bool|int
     * @throws \Exception
     */
    public function render($path = null)
    {
        if ($path === null) {
            $path = app()->path() . '/Models/' . $this->className . '.php';
        }
        if (is_file($path)) {
            throw new \Exception(sprintf("model file (%s) is exist", realpath($path)));
        }
        return file_put_contents($path, $this->getTemplate());
    }

    public function getTemplate()
    {
        $t = $this->genPHPStart();
        $t .= $this->genNamespace();
        $t .= $this->genUses();
        $t .= $this->genClass();
        $t .= $this->genUseTraits();
        $t .= $this->genAttributes();
        $t .= $this->genGetters();
        $t .= $this->genSetters();
        $t .= $this->genGetSetters();
        $t .= $this->genPHPEnd();
        return $t;
    }

    private function genSpace($n = 1)
    {
        return str_repeat(self::SPACE, $n);
    }

    private function genPHPStart()
    {
        return '<?php' . PHP_EOL . PHP_EOL;
    }

    private function genPHPEnd()
    {
        return '}' . PHP_EOL;
    }

    private function genNamespace()
    {
        return "namespace {$this->namespace};" . PHP_EOL . PHP_EOL;
    }

    private function genUses()
    {
        $t = '';
        foreach ($this->uses as $use) {
            $t .= "use {$use};" . PHP_EOL;
        }
        $t .= PHP_EOL;
        return $t;
    }

    private function genClass()
    {
        //class部分的声明
        $t = "class {$this->className}";
        if ($this->extend) {
            $t .= " extends {$this->extend}";
        }
        if ($this->implements) {
            $t .= " implements";
            foreach ($this->implements as $implement) {
                $t .= " {$implement},";
            }
            $t = substr($t, 0, -1);
        }
        $t .= PHP_EOL . '{' . PHP_EOL;
        return $t;
    }

    private function genUseTraits()
    {
        $t = '';
        if ($this->useTraits) {
            foreach ($this->useTraits as $useTrait) {
                $t .= $this->genSpace() . "use {$useTrait};" . PHP_EOL;
            }
            $t .= PHP_EOL;
        }
        return $t;
    }

    private function genAttributes()
    {
        $t = '';
        if ($this->attributes) {
            foreach ($this->attributes as $attribute) {
                $t .= $this->genSpace() . $attribute->getType() . ' $' . $attribute->getName();
                if ($attribute->getValue() !== null) {
                    $t .= " = {$attribute->getValue()}";
                }
                $t .= ';' . PHP_EOL;
            }
            $t .= PHP_EOL;
        }
        return $t;
    }

    private function genGetters()
    {
        $t = '';
        foreach ($this->getters as $field) {
            $t .= $this->genGetter($field);
        }
        return $t;
    }

    private function genSetters()
    {
        $t = '';
        foreach ($this->setters as $field) {
            $t .= $this->genGetter($field);
        }
        return $t;
    }

    private function genGetSetters()
    {
        $t = '';
        foreach ($this->getSetters as $field) {
            $t .= $this->genSetter($field);
            $t .= $this->genGetter($field);
        }
        return $t;
    }

    /**
     * @param Getter $field
     * @return string
     */
    private function genGetter($field)
    {
        $t = $this->genSpace() . '/**' . PHP_EOL;
        $t .= $this->genSpace() . ' * return ' . $field->getReturnValueType() . PHP_EOL;
        $t .= $this->genSpace() . ' */' . PHP_EOL;
        $t .= $this->genSpace() . 'public function get' . $this->snakeToBigCamel($field->getField()) . '()' . PHP_EOL;
        $t .= $this->genSpace() . '{' . PHP_EOL;
        $t .= $this->genSpace(2) . 'return $this->' . $field->getField() . ';' . PHP_EOL;
        $t .= $this->genSpace() . '}' . PHP_EOL . PHP_EOL;
        return $t;
    }

    /**
     * @param Getter $field
     * @return string
     */
    private function genSetter($field)
    {
        $t = $this->genSpace() . 'public function set' . $this->snakeToBigCamel($field->getField()) . '($value)' . PHP_EOL;
        $t .= $this->genSpace() . '{' . PHP_EOL;
        $t .= $this->genSpace(2) . '$this->' . $field->getField() . ' = $value;' . PHP_EOL;
        $t .= $this->genSpace() . '}' . PHP_EOL . PHP_EOL;
        return $t;
    }

    /**
     * 下划线蛇形法转小驼峰法
     *
     * @param $str
     * @return string
     */
    public function snakeToSmallCamel($str)
    {
        $length = strlen($str);
        $new = '';
        for ($i = 0; $i < $length; $i++) {
            if ($str{$i} == '_') {
                $i++;
                if ($i < $length) {
                    $new .= strtoupper($str{$i});
                }
            } else {
                $new .= $str{$i};
            }
        }
        return $new;
    }

    /**
     * 下划线蛇形法转大驼峰法
     *
     * @param $str
     * @return string
     */
    public function snakeToBigCamel($str)
    {
        return ucfirst($this->snakeToSmallCamel($str));
    }

    /**
     * 驼峰法转蛇形法
     * @param string $str
     * @return string
     */
    public function camelToSnake($str)
    {
        $length = strlen($str);
        $new = '';
        for ($i = 0; $i < $length; $i++) {
            $ascii = ord($str{$i});
            if ($ascii >= 65 && $ascii <= 90) {
                if ($i != 0) {
                    $new .= '_';
                }
                $new .= strtolower($str{$i});
            } else {
                $new .= $str{$i};
            }
        }
        return $new;
    }


}