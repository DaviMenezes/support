<?php

namespace Dvi\Support\Service;

use Dvi\Support\Model\ModelAdianti;
use stdClass;

/**
 *  ReflectionHelpers
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
trait ReflectionHelpers
{
    public static $reflection_properties = [];

    /**@return self*/
    public static function properties($alias = null)
    {
        /**@var ModelAdianti $called_class*/
        $called_class = get_called_class();

        $props = self::getReflectionProperties($called_class);
        if (!$alias or ($props and isset($alias) and isset($props->$alias))) {
            return $props;
        }
        //clear alias in properties
        foreach ($props as $key => $prop) {
            if (strrpos($prop, '.') !== false) {
                $array = explode('.', $prop);
                unset($array[0]);
                $props->$key = $array[1];
            }
        }
        $properties = new $called_class();

        $properties->alias = $alias;
        foreach ($props as $key => $prop) {
            if ($key == 'alias') {
                continue;
            }
            $properties->$key = $alias . '.' . $prop;
        }

        return self::$reflection_properties[$called_class] = $properties;
    }

    public static function getReflectionProperties($class = null)
    {
        $class = $class ?? get_called_class();
        return self::$reflection_properties[$class] = self::$reflection_properties[$class] ?? props($class);
    }

    public function alias()
    {
        return $this->alias;
    }

    /**
     * Alias to properties()
     * @return self
     */
    public static function prop()
    {
        return props(self::class);
    }

    /**
     * Alias to properties()
     * @return self
     */
    public static function p()
    {
        return props(self::class);
    }
}
