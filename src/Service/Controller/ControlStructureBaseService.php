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
    protected $param;
    protected $environment;
    /**@var FlashBag*/
    protected static $flashbag;

    protected function initialize($param)
    {
        try {
            $this->param = $param;
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
            $id = http()->get('id');
            static::$currentObject = $model::find($id);
        } catch (Exception $e) {
            throw new Exception('Criando objeto corrente: '.$e->getMessage());
        }
    }

    private function setLoggedUser()
    {
        $this->loggedUser = User::find(TSession::getValue('userid'));
    }

    protected function getEnvironment()
    {
        return $this->environment = $this->environment ?? $this->setEnvironment();
    }

    protected function inDevelopment(): bool
    {
        return $this->getEnvironment() == 'development';
    }

    private function setEnvironment()
    {
        $ini = AdiantiApplicationConfig::get();
        $this->environment = $ini['general']['environment'];
        return $this->environment;
    }

    abstract protected static function getModel():string;
}
