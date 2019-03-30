<?php

namespace Dvi\Support\Acl\Model;

use App\Model\Dvi\Base\ModelAdianti;

/**
 *  RuleGroupModuleCustomActions
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @property string $id
 * @property string $group_module_id
 * @property string $action_name
 */
class AclRuleGroupModuleCustomActions extends ModelAdianti
{
    public const TABLENAME = 'acl_rule_group_module_custom_actions';

    protected $fillable = ['id', 'group_module_id', 'action_name'];
}
