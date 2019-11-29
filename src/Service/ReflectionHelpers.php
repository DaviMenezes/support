<?php

namespace Dvi\Support\Service;

use BaconQrCode\Common\Mode;
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

        $props = self::getReflectionProperties($called_class, $alias);
        if (!$alias or ($props and isset($alias) and isset($props->alias))) {
            if (is_a($props, stdClass::class)) {
                return self::getCalledClassAsModel($props);
            }
            return  $props;
        }
        //clear alias in properties
        $props = is_a($props, stdClass::class) ? $props : (object)$props->getData();
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

        $alias = '_alias-'.$alias;
        $key = $called_class.$alias;
        return self::$reflection_properties[$key] = $properties;
    }

    private static function getCalledClassAsModel($properties):ModelAdianti
    {
        $called_class = get_called_class();
        /**@var ModelAdianti $obj*/
        $obj = new $called_class();
        $obj->fromArray((array)$properties);

        self::$reflection_properties[$called_class] = $obj;
        return $obj;
    }


    public static function getReflectionProperties($class = null, $alias = null)
    {
        $class = $class ?? get_called_class();
        $alias = $alias ? '-alias_' . $alias : null;
        $key = $class . $alias;

        if (!isset(self::$reflection_properties[$key])) {
            self::$reflection_properties[$key] = props($class);
        }
        return self::$reflection_properties[$key];
    }

    public function alias()
    {
        return $this->alias ?? null;
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
