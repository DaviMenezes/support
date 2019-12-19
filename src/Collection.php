<?php

namespace Dvi\Support;

use Dvi\Corda\Support\Corda;
use Dvi\Corda\Support\Corda as str;
use Tightenco\Collect\Support\Collection as SupportCollection;

/**
 *  Collection
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @see https://t.me/davimenezes
 * @method Corda first(callable $callback = null, $default = null)
 */
class Collection extends SupportCollection
{
    /**
     * @param $value
     * @param null $glue
     * @return str
     */
    public function implode($value, $glue = null)
    {
        return str(parent::implode($value, $glue));
    }

    /**
     * @param $key
     * @param null $default
     * @return str|mixed
     */
    public function get($key, $default = null)
    {
        $result = parent::get($key, $default);
        if (is_string($result)) {
            return new str($result);
        }
        return $result;
    }

    /**@return str*/
    public function route()
    {
        $this->map(function ($item, $key) use (&$url) {
            $url .= '/'.$key.'/'.$item;
        });
        return str($url);
    }

    /**@return Collection*/
    public function firstWhere($key, $operator, $value = null)
    {
        $result = parent::firstWhere($key, $operator, $value);
        if (is_array($result)) {
            return collect($result);
        }
        return $result;
    }
}
