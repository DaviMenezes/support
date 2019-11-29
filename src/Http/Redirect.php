<?php

namespace Dvi\Support\Http;

use Adianti\Core\AdiantiCoreApplication;
use Adianti\Widget\Base\TScript;

/**
 *  Redirect
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
class Redirect
{
    protected $method;
    protected $class;
    protected $param;

    public function __construct($class = null)
    {
        $this->class = $class;
    }

    public function method($method)
    {
        $this->method = $method;
        return $this;
    }

    public function params(array $params)
    {
        $this->param = array_merge($this->param ?? [], $params);
        return $this;
    }

    public function load()
    {
        if (!$this->class) {
            return;
        }
        $this->prepareMethod();

        AdiantiCoreApplication::loadPage($this->class, $this->method, $this->param);
    }

    public function go()
    {
        if (!$this->class) {
            return;
        }
        $this->prepareMethod();

        AdiantiCoreApplication::gotoPage($this->class, $this->method, $this->param);
    }

    protected function prepareMethod(): void
    {
        if (isset($this->method) && !method_exists($this->class, $this->method)) {
            $this->method = 'index';
        }
        if (isset($this->method) && !method_exists($this->class, $this->method)) {
            $this->method = null;
        }
    }

    public function url(string $url)
    {
        TScript::create('window.location.replace("'.$url.'");');
    }
}
