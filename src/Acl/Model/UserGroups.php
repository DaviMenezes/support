<?php

namespace Dvi\Support\Acl\Model;

use App\Model\Dvi\Base\ModelAdianti;

/**
 *  UserRuleGroups
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @property string $id
 * @property string $user_id
 * @property string $rule_group_id
 */
class UserGroups extends ModelAdianti
{
    public const TABLENAME = 'acl_user_groups';

    protected $fillable = ['id', 'user_id', 'rule_group_id'];
}
