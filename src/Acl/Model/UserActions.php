<?php

namespace Dvi\Support\Acl\Model;

use App\Model\Dvi\Base\ModelAdianti;

/**
 *  UserPermissions
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @property string $id
 * @property string $user_id
 * @property string $module_id
 * @property string $action_id
 */
class UserActions extends ModelAdianti
{
    public const TABLENAME = 'acl_user_actions';

    protected $fillable = ['id', 'user_id', 'module_id', 'action_id'];
}
