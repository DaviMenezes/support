<?php

namespace Dvi\Support\Service;

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
    public static function properties()
    {
        return props(self::class);
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
