<?php

namespace Dvi\Acl\Module;

use App\Acl\Module\UserPermission;
use Dvi\Acl\Permission\BasicPermission;

/**
 *  Modules
 * By default nonexistent method return BasicPermission
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @method static BasicPermission acl()
 */
abstract class Modules
{
    public static function user()
    {
        return new UserPermission();
    }

    public static function __callStatic($name, $arguments)
    {
        return new BasicPermission();
    }
}
