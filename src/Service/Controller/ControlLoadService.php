<?php

namespace Dvi\Support\Service\Controller;

use Dvi\Support\Http\Request;
use Exception;
use ReflectionClass;

/**
 *  ControlLoadService
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @see https://t.me/davimenezes
 */
class ControlLoadService
{
    public function __construct()
    {
    }

    public function getData(Request $request, ReflectionClass $reflection_class, $method)
    {
        try {
            if (!$reflection_class->hasMethod($method)) {
                return null;
            }
            $parameters = collect($reflection_class->getMethod($method)->getParameters());
            if ($parameters->count() == 0) {
                return null;
            }
            /**@var \ReflectionParameter $first*/
            $first = $parameters->first();

            if (!empty($first->getType()) and $first->getType()->getName() == Request::class) {
                return $request;
            }

            return $request->all();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
