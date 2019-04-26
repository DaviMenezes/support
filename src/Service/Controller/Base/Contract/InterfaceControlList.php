<?php

namespace Dvi\Support\Service\Controller\Base\Contract;

/**
 *  InterfaceControlList
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
interface InterfaceControlList
{
    public function loadDataGrid($param);

    public function onSearch($param);
}
