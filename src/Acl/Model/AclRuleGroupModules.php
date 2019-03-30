<?php

namespace Dvi\Support\Acl\Model;

use App\Model\Dvi\Base\ModelAdianti;

/**
 *  RuleGroupModules
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
class AclRuleGroupModules extends ModelAdianti
{
    public const TABLENAME = 'acl_rule_group_modules';

    protected $fillable = ['id', 'group_id', 'module_id'];
}
