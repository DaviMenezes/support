<?php

namespace Dvi\Support\Environment;

/**
 * @author     Davi Menezes (davimenezes.dev@gmail.com)
 * @copyright  Copyright (c) 2019.
 * @see https://github.com/DaviMenezes
 */
class Environment
{
    protected static $environment;

    private function __construct(EnvironmentEnum $environment)
    {
    }

    public static function set(EnvironmentEnum $environment)
    {
        //limit as a constant
        if (isset(self::$environment)) {
            return;
        }
        self::$environment = $environment;
    }

    public static function isProduction()
    {
        return self::$environment == 'production';
    }

    public static function isDevelopment()
    {
        return self::$environment == 'development';
    }
}
