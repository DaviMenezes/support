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

use Dvi\Corda\Support\Corda;
use Dvi\Corda\Support\Money;
use Dvi\Support\Collection;
use Dvi\Support\Http\Request;
use Dvi\Support\Http\Web;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Parser\IntlLocalizedDecimalParser;

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

function http():Request
{
    return Request::instance();
}

function removeDirectory($path)
{
    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);
    return;
}

/**
 * @param int $dollar
 * @return Money
 */
function money($dollar = 0)
{
    $dollar = empty($dollar) ? 0 : $dollar;
    $dollar = str_replace([',', '.'], '', $dollar);
    $dollar = str($dollar)->removeLeft('00');
    if ($dollar->str() !== '0') {
        $dollar = $dollar->removeLeft('0')->str();
    }
    $money = Money::USD($dollar);
    return $money;
}

/**
 * Return properties of class
 * @param string $class
 * @return stdClass
 * @throws ReflectionException
 */
function props(string $class)
{
    $rf = (new ReflectionClass($class))->getDocComment();

    $obj = new stdClass();
    str($rf)->lines()->filter()->map(function (Corda $line, $key) use (&$obj) {
        if (!$line->contains('@property')) {
            return;
        }
        //if line format is incorrect
        if (!$line->contains('$')) {
            return;
        }
        $property = $line->lastStr('$')->removeLeft('$')->str();
        $obj->$property = $property;
    });
    return $obj;
}

function redirect($class)
{
    return new \Dvi\Support\Http\Redirect($class);
}
