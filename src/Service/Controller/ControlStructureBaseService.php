<?php

namespace Dvi\Support\Service\Controller;

use Adianti\Core\AdiantiApplicationConfig;
use Adianti\Registry\TSession;
use App\Control\User\Model\User;
use Dvi\Support\Http\Request;
use Exception;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

trait ControlStructureBaseService
{
    /**@var FlashBag*/
    protected static $flashbag;

    protected function initialize($param)
    {
        try {
            $this->http = Request::instance();
            self::$flashbag = new FlashBag();
            $this->setCurrentObject();

            $this->setLoggedUser();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected static function addFlash(string $type, $message)
    {
        self::$flashbag->add($type, $message);
    }

    protected static function getFlash($type)
    {
        return self::$flashbag->get($type);
    }

    protected function setCurrentObject()
    {
        try {
            if (isset(self::$currentObject)) {
                return self::$currentObject;
            }
            $class = get_called_class();
            $model = $class::getModel();
            $id = http()->query('id');
            $this->createCurrentObject($model, $id);
        } catch (Exception $e) {
            throw new Exception('Criando objeto corrente: '.$e->getMessage());
        }
    }

    protected function setLoggedUser()
    {
        $this->loggedUser = User::find(TSession::getValue('userid'));
    }

    abstract protected static function getModel():string;

    protected function createCurrentObject($model, $id): void
    {
        try {
            static::$currentObject = new $model($id);
        } catch (Exception $exception) {
            static::$currentObject = new $model();
        }
    }
}
