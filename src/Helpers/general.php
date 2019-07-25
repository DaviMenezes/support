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

use Adianti\Widget\Base\TScript;
use Dvi\Corda\Support\Corda;
use Dvi\Corda\Support\Money;
use Dvi\Support\Collection;
use Dvi\Support\Http\Request;
use Dvi\Support\Http\Web;
use Dvi\Support\Notify;
use Dvi\Component\TemplateEngine\TemplateEngine;
use Dvi\Component\TemplateEngine\BladeOneInstance;
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

    $params = Web::editingParameters();
    foreach ($params as $key => $param) {
        $param_key = $param['key'];
        $param_value = $param['value'] ?? null;

        if (http()->isGet()) {
            $id = http()->query('id');
        } else {
            $id = http()->body('id');
        }
        if (!$request->has($param_key) or (isset($param_value) and $request->has($param_key) and $id !== $param_value)) {
            return false;
        }
    }
    return true;
}

/**
 * http request
 * @return Request
 */
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

/**Format value to dollar*/
function toDollar($value = "0,00")
{
    $value = str_replace('.', '', $value);
    $value = str_replace(',', '.', $value);
    $value = number_format($value, 2);
    return $value;
}
/**Format value to database*/
function realToDatabase($value)
{
    $value = str_replace('.', '', $value);
    $value = str_replace(',', '.', $value);
    $value = (real)$value;
    return $value;
}

/**Format value to Real*/
function toReal($value = '0.00', $decimals = 2)
{
    $value = str_replace(',', '', $value);
    $value = number_format($value, 2, ',', '.');
    return $value;
}

/**Format value to database format*/
function dollarToDatabase($value)
{
    $value = str_replace(',', '', $value);
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

function view(string $view, array $data = null)
{
    $blade = new BladeOne(VIEW_PATH, VIEW_CACHE_PATH, BLADE_MODE);
    $templateEngine = TemplateEngine::instance(BladeOneInstance::class);
    echo $blade->run($view, $data);
}

function notityComponentWithViolation()
{
    foreach (http()->obj()->getSession()->getFlashBag()->get('validation_violation', []) as $key => $message) {
        $msg_file_erros = '<div class="flash-notice">' . $message['violation']->getMessage() . '</div>';
        $attribute = str($message['attribute'])->lastStr('.')->str();
        $js = "$('#" . $attribute . "').after('" . $msg_file_erros . "')";
        TScript::create($js);
    }
}
