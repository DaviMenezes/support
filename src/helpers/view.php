<?php

/**
 *  view helpers
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @see https://t.me/davimenezes
 */

function view(string $view, array $data = null)
{
    $blade = new BladeOne(VIEW_PATH, VIEW_CACHE_PATH, BLADE_MODE);

    echo $blade->run($view, $data);
}
