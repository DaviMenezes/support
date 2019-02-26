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

use App\Http\Request;
use App\Http\Router;
use Dvi\Support\Collection;
use Dvi\Support\Corda;
use Dvi\Adianti\Helpers\Reflection;
use eftec\bladeone\BladeOne;

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

    if (($request->routeInfo()->method() == 'onEdit' and $request->has('id')) or ($request->has('editing') or $request->attr('editing'))) {
        return true;
    }
    return false;
}
