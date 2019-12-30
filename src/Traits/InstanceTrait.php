<?php

namespace MakeModel\Traits;

trait InstanceTrait
{
    private static $instances;

    /**
     * @return static
     */
    public static function getInstance()
    {
        $className = get_called_class();
        $args = func_get_args();
        //若$args中有resource类型的参数,则无法区分同一个类的不同实例
        $key = md5($className . ':' . serialize($args));
        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = new $className(...$args);
        }
        return self::$instances[$key];
    }

}