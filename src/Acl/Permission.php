<?php

namespace Dvi\Support\Acl;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
/**
 *  Permission
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
trait Permission
{
    public static function canCreate($module = null, $user_id = null)
    {
        $modules = Capsule::table('user_rule_groups as urg')->select([
            'urg.user_id',
            'rg.id AS group_id',
            'rg.NAME AS group_name',
            'm.NAME AS module',
            'uat.NAME AS action'])
            ->leftJoin('rule_groups as rg', 'rg.id', '=', 'urg.rule_group_id')
            ->leftJoin('rule_group_modules as rgm', 'rgm.group_id', '=', 'rg.id')
            ->leftJoin('rule_group_module_actions as rgma', 'rgma.group_module_id', '=', 'rgm.id')
            ->leftJoin('user_action_types as uat', 'uat.id', '=', 'rgma.user_action_type_id')
            ->leftJoin('modules as m', 'm.id', '=', 'rgm.module_id')
            ->where('urg.user_id', '=', $user_id);

        $result = $modules->get();

        return $result;
    }
}
