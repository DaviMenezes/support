<?php

namespace Dvi\Support\Acl\Model;

use App\Model\Dvi\Base\ModelAdianti;

/**
 *  UserActionTypes
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @property string $id
 * @property string $name
 */
class AclUserActionTypes extends ModelAdianti
{
    public const TABLENAME = 'acl_user_action_types';

    protected $fillable = ['id', 'name'];
}
