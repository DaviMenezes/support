<?php

/**
 *  route helpers
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @see https://t.me/davimenezes
 */

function route(string $route = null, $params = null)
{
    if ($route) {
        $route = str($route)->removeLeft('//')->removeRight('//')->ensureLeft('/');
    } else {
        $route = Router::getStaticRouteInfo()->route();
    }
    $route_params = '';
    collect($params)->map(function ($value, $key) use (&$route_params) {
        $route_params .= str($key)->append('/')->append($value)->ensureLeft('/');
    });

    $route = $route->append($route_params)->removeRight('/')->str();
    return $route;
}

function urlRoute(string $route = null, $params = null)
{
    $url = routeBase().route($route, $params);
    return $url;
}

function routeBase()
{
    $baseUrl = Request::getBaseUrl();
    if ($baseUrl->contains('.php')) {
        $baseUrl = $baseUrl->removeRight('engine.php');
        $baseUrl = $baseUrl->removeRight('index.php');
    }
    return $baseUrl->removeRight('/');
}

function routeByClass($controller)
{
    $route_info = Router::routes()->first(function ($route_info, $route) use ($controller) {
        return Reflection::shortName($route_info->class()) == $controller;
    });
    if ($route_info) {
        return $route_info->route();
    }
    return '/emptypage';
}