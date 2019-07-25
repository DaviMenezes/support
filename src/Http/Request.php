<?php

namespace Dvi\Support\Http;

use Dvi\Support\Service\Controller\ControlLoadService;
use Dvi\Corda\Support\Corda;
use FastRoute\Dispatcher;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as FoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Tightenco\Collect\Support\Collection;

/**
 *  Request
 *
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @see https://t.me/davimenezes
 * @method static Corda getScriptName()
 * @method static Corda getRequestUri()
 * @method static Corda getRealMethod()
 * @method static Corda getBaseUrl()
 * @method isXmlHttpRequest()
 * @property-read  Request static $instance
 */
class Request
{
    protected static $request;
    private static $instance;

    private function __construct()
    {
        self::$request = FoundationRequest::createFromGlobals();
    }

    public static function instance()
    {
        return self::$instance = self::$instance ?? new self();
    }

    public function obj(): FoundationRequest
    {
        return self::$request;
    }

    public function sessionFlash($param)
    {
        return $this->obj()->getSession()->getFlashBag()->get($param, [])[0] ?? null;
    }

    public function query($key, $default = null, $decode = null)
    {
        $result = $this->obj()->query->get($key, $default);
        if ($decode) {
            return base64_decode($result);
        }
        return $result;
    }

    public function request($key, $default = null, $decode = null)
    {
        return $this->collect()->get($key, $default);
    }

    public function body($key, $default = null)
    {
        return self::$request->request->get($key, $default);
    }

    public function result(): ParameterBag
    {
        if (self::$request->getRealMethod() == 'POST') {
            return self::$request->request;
        }
        if (self::$request->getRealMethod() == 'GET') {
            return self::$request->query;
        }
    }

    public function all(): array
    {
        $get = self::$request->query->all();
        $post = self::$request->request->all();
        $attributes = self::$request->attributes->all();
        return array_merge($get, $post, $attributes);
    }

    public function collect(): Collection
    {
        return \collection($this->all())->filter();
    }

    public function postCollection(): Collection
    {
        return \collection(self::$request->request->all());
    }

    public function has($key)
    {
        return $this->collect()->has($key);
    }

    public function add(array $parameters)
    {
        $this->obj()->attributes->add($parameters);
    }

    public function uriGet(): string
    {
        $this->collect()->map(function ($key, $value) use (&$uri) {
            $uri .= '/' . $key . '/' . $value;
        });

        return $uri;
    }

    public function attr($key, $default = null)
    {
        return $this->obj()->attributes->get($key, $default);
    }

    public function routeInfo() : RouteInfo
    {
        return $this->attr('route_info');
    }

    /**
     * Return the request response
    */
    public function getContent()
    {
        $routeInfo = $this->routeInfo();
        $status = $routeInfo->getStatus();
        switch ($status) {
            case Dispatcher::NOT_FOUND:
                $response = new Response('404 Não encontrado', 404, []);
                return $response->getContent();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = new Response('Not allowed', 500, []);
                return $response->getContent();
                break;
            case Dispatcher::FOUND:
                try {
                    $class = $routeInfo->class();
                    $method = $routeInfo->method() ?? 'show';

                    $reflection_class = new \ReflectionClass($class);

                    if ($reflection_class->isSubclassOf(DviControl::class)) {
                        $result = $this->executeRequest($class, $method);
                        return $result;
                    }

                    ob_start();
                    $this->executeRequest($class, $method);
                    $result = ob_get_contents();
                    ob_clean();

                    $services = Web::services();
                    if (in_array($class, $services)) {
                        return $result;
                    }

                    //Prepare response to template
                    $json = new \stdClass();
                    $json->page_title = APPLICATION_NAME;
                    $json->page_content = $result;
                    return json_encode($json);
                } catch (\Exception $e) {
                    $msg_error = 'Há um problema com a solicitação. Verifique.';
                    $msg_error .= "<br>Erro: " . $e->getMessage().' File: '.$e->getFile().' Line: '. $e->getLine();
                    $result['error'] = $msg_error;
                    return json_encode($result);
                }
        }
    }

    /**
     * @param \ReflectionClass $rf_class
     * @param string|null $method
     * @return Request|array
     * @throws \Exception
     */
    protected function getDataToRequest(\ReflectionClass $rf_class, ?string $method)
    {
        $service_load = new ControlLoadService();
        $data = $service_load->getData($this, $rf_class, $method);
        return $data;
    }

    private function executeRequest($class, $method)
    {
        $reflection_class = new \ReflectionClass($class);
        if (!$reflection_class->hasMethod($method)) {
            throw new \Exception('The method does not exist');
        }

        if (!$reflection_class->isInstantiable()) {
            throw new \Exception('The class ' . $class . ' is not instantiable');
        }
        //Todo verificar classe DviControl
        /**@var DviControl $obj*/
        $data_construct = $this->getDataToRequest($reflection_class, '__construct');
        $data_method = $this->getDataToRequest($reflection_class, $method);

        if ($reflection_class->getMethod($method)->isStatic()) {
            $obj = new $class($data_construct);
            $result = $obj::$method($data_method);

            return $result;
        }

        $obj = new $class($data_construct);

        $result = $obj->$method($data_method);

        if ($method != 'show' and $reflection_class->hasMethod('show')) {
            $data = $this->getDataToRequest($reflection_class, 'show');
            $result = $obj->show($data);
        }

        return $result;
    }

    public function isGet()
    {
        if (self::$request->getRealMethod() == 'GET') {
            return true;
        }
        return false;
    }

    public function isPost()
    {
        if (self::$request->getRealMethod() == 'POST') {
            return true;
        }
        return false;
    }

    public function __call($name, $arguments)
    {
        return $this->callMethod($name, $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::callMethod($name, $arguments);
    }

    private static function callMethod($name, $arguments)
    {
        $result = '';
        if (method_exists(self::$request, $name)) {
            $result = self::$request->$name($arguments);
        }
        if (is_string($result)) {
            return str($result);
        }
        return $result;
    }
}
