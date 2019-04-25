<?php

namespace Dvi\Support\Http;

use Adianti\Core\AdiantiCoreApplication;

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

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function method($method)
    {
        $this->method = $method;
    }

    public function param($param)
    {
        $this->param = $param;
    }

    public function load()
    {
        $this->prepareMethod();

        AdiantiCoreApplication::loadPage($this->class, $this->method, $this->param);
    }

    public function go()
    {
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
}
