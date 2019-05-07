<?php

namespace Dvi\Support\Service\Controller\Base;

use Adianti\Control\TPage;
use Adianti\Widget\Dialog\TMessage;
use Dvi\Support\Http\Request;
use Dvi\Support\Service\Controller\ControlStructureBaseService;
use Dvi\Support\Service\Database\Transaction;

abstract class ControlBase extends TPage
{
    protected static $database;
    public static $currentObject;
    protected $loggedUser;
    protected $already_create_layout;
    protected $panel;
    /**@var Request*/
    protected $http;

    use ControlStructureBaseService;

    public function __construct($param, $mantem_conexao = false)
    {
        try {
            if (!$mantem_conexao) {
                Transaction::open();
            }

            parent::__construct();

            self::$database = $this->getDatabase();

            $this->initialize($param);

            if (!$mantem_conexao) {
                Transaction::close();
            }
        } catch (\Exception $e) {
            Transaction::rollback();
            new TMessage('error', $e->getMessage());
            die();
        }
    }

    public function index()
    {
        try {
            $class = get_called_class();
            Transaction::open($class::getDatabase());

            $this->createLayout();

            Transaction::close();
        } catch (\Exception $e) {
            Transaction::rollback();
            new TMessage('error', $e->getMessage());
        }
    }

    abstract protected function createLayout();

    abstract protected function getPageTitle();

    abstract protected static function getDatabase();

    protected function setLayoutContainer()
    {
        parent::add($this->form);
    }

    public function run()
    {
        $request = http();

        if (!$_GET) {
            return;
        }
        $class  = $request->url('class');
        $method = $request->url('method');

        $parameters = $this->getParameters($method, $class, $request);

        if ($class) {
            $object = $class == get_class($this) ? $this : new $class;
            if (is_callable(array($object, $method))) {
                call_user_func(array($object, $method), $parameters);
            }
        } elseif (function_exists($method)) {
            call_user_func($method, $parameters);
        }
    }

    protected function getParameters($method, $class, Request $request)
    {
        if (!$method) {
            return null;
        }

        $rf = new \ReflectionClass($class);
        $parameters = $rf->getMethod($method)->getParameters();

        if (count($parameters)) {
            $parameter = $parameters[0];
            if ($parameter->name == 'request') {
                return $request;
            }
            return $request->all();
        }
        return null;
    }
}
