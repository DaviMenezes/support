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
    /**@return self*/
    public static function properties($alias = null)
    {
        /**@var ModelAdianti $called_class*/
        $called_class = self::class;

        $properties = $called_class::getReflectionProperties();
        if ($properties) {
            return $properties;
        }
        $props = props($called_class);

        if (!$alias) {
            return self::$reflection_properties[$called_class] = $props;
        }

        $properties = new stdClass();

        $properties->alias = $alias;
        foreach ($props as $key => $prop) {
            $properties->$key = $alias . '.' . $prop;
        }

        return self::$reflection_properties[$called_class] = $properties;
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
