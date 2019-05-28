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
function money($dollar = 0.00)
{
    $dollar = str_replace([',', '.'], '', $dollar);
    $money = Money::USD($dollar);
    return $money;
}

function toDollar($value = "0,00")
{
    $value = str_replace('.', '', $value);
    $value = str_replace(',', '.', $value);
    $value = number_format($value, 2);
    return $value;
}

function toReal($value = '0.00', $decimals = 2)
{
    $value = str_replace(',', '', $value);
    $value = number_format($value, 2, ',', '.');
    return $value;
}

function dbFormat($value)
{
    $value = (double)number_format($value, 2, '.', '');
    return $value;
}

/**
 * Return properties of class
 * @param string $class
 * @return stdClass
 */
function props(string $class)
{
    try {
        $rf = (new ReflectionClass($class))->getDocComment();
    } catch (ReflectionException $e) {
        $rf = '';
    }

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

/**
 * @param string $class
 * @return \Dvi\Support\Http\Redirect
 */
function redirect(string $class = null)
{
    return new \Dvi\Support\Http\Redirect($class);
}

/**Return current date
 * @return string|false a formatted date string. If a non-numeric value is used for
 * timestamp, false is returned and an
 * E_WARNING level error is emitted.
 */
function now()
{
    return date('Y-m-d H:i:s');
}

/**Checks if value is empty
 * @param $value
 * @return bool
 */
function isEmpty($value)
{
    return !trim($value);
}

/**Check if value is not empty
 * @param $value
 * @return bool
 */
function notEmpty($value)
{
    return !isEmpty($value);
}
