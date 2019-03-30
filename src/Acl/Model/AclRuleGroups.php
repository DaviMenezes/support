<?php

namespace Dvi\Support\Acl\Model;

use App\Model\Dvi\Base\ModelAdianti;

/**
 *  RuleGroups
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @property string $id
 * @property string $name
 */
class AclRuleGroups extends ModelAdianti
{
    public const TABLENAME = 'acl_rule_groups';

    protected $fillable = ['id','name'];
}
