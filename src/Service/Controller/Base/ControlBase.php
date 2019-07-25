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

    public function __construct($param, $keep_connection = false)
    {
        try {
            if (!$keep_connection) {
                Transaction::open();
            }

            parent::__construct();

            self::$database = $this->getDatabase();

            $this->initialize($param);

            if (!$keep_connection) {
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
        if (!http()->query('method')) {
            return;
        }
        $parameters = self::getParameters();

        $class  = http()->query('class');
        $method = http()->query('method');
        if ($class) {
            $object = $class == get_class($this) ? $this : new $class;
            if (is_callable(array($object, $method))) {
                call_user_func(array($object, $method), $parameters);
            }
        } elseif (function_exists($method)) {
            call_user_func($method, $parameters);
        }
    }

    public static function getParameters()
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
            if (!count($parameters)) {
                return null;
            }

            $parameter = $parameters[0];
            $type = $parameter->getType();
            if ($type and $type->getName() == Request::class) {
                return http();
            }
            return http()->all();
        } catch (\ReflectionException $exception) {
            throw $exception;
        }
    }
}
