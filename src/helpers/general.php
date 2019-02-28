<?php

/**
 *  helpers
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @see https://t.me/davimenezes
 */

use Dvi\Support\Http\Request;
use Dvi\Support\Collection;
use Dvi\Corda\Support\Corda;
use Dvi\Support\Http\Web;

function collection($value)
{
    return new Collection($value);
}

if (! function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return Collection
     */
    function collect($value = null)
    {
        return new Collection($value);
    }
}

if (!function_exists('str')) {
    /**
     * Create a collection from the given value.
     *
     * @param $str string
     * @return Corda
     */
    function str($str)
    {
        return new Corda($str);
    }
}

function editing()
{
    $request = Request::instance();

    $params = Web::editingParameters();
    foreach ($params as $key => $param) {
        $param_key = $param['key'];
        $param_value = $param['value'] ?? null;

        if (!$request->has($param_key) or (isset($param_value) and $request->has($param_key) and $request->get($param_key) !== $param_value)) {
            return false;
        }
    }
    return true;
}

function request():Request
{
    return Request::instance();
}
