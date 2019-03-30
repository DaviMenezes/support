<?php

namespace Dvi\Support\Acl\Model;

use App\Model\Dvi\Base\ModelAdianti;

/**
 *  RuleGroupModuleActions
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @property string $id
 * @property string $group_module_id
 * @property string $user_action_type_id
 */
class AclRuleGroupModuleActions extends ModelAdianti
{
    public const TABLENAME = 'acl_rule_group_module_actions';

    protected $fillable = ['id', 'group_module_id', 'user_action_type_id'];
}
