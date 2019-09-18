<?php

namespace Dvi\Support\Service;

use Dvi\Support\Http\Request;
use ReflectionClass;

class ClassParameters
{
    private function __construct()
    {
    }

    public static function getContructParameters()
    {
        if (!http()->query('class')) {
            return null;
        }
        $rf = new ReflectionClass(http()->query('class'));
        $parameters = $rf->getConstructor()->getParameters();

        return self::getParameters($parameters);
    }

    public static function getMethodParameters()
    {
        try {
            if (!http()->query('class')) {
                return null;
            }
            $rf = new \ReflectionClass(http()->query('class'));

            $parameters = [];
            if (!http()->query('method')) {
                if (!$rf->hasMethod('__construct')) {
                    return http()->all();
                }
                $parameters = $rf->getConstructor()->getParameters();
            } elseif ($rf->hasMethod(http()->query('method'))) {
                $parameters = $rf->getMethod(http()->query('method'))->getParameters();
                if (!$rf->getMethod(http()->query('method'))->isStatic()) {
                    $construct_parameters = $rf->getConstructor()->getParameters();
                    $all = array_merge($parameters, $construct_parameters);
                    $parameters = collect($all)->filter()->all();
                }
            }
            return self::getParameters($parameters);
        } catch (\ReflectionException $exception) {
            throw $exception;
        }
    }

    /**@param Request|array $parameters
     * @return array|Request|null
     */
    private static function getParameters($parameters)
    {
        if (!count($parameters)) {
            return null;
        }
        /**@var \ReflectionParameter $parameter*/
        $parameter = $parameters[0];
        $type = $parameter->getType();
        if ($type and $type->getName() == Request::class) {
            return http();
        }
        return http()->all();
    }
}
