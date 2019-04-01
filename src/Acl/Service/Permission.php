<?php

namespace Dvi\Support\Acl\Service;

use Dvi\Acl\Model\GroupActions;
use Dvi\Support\Acl\Model\Groups;
use Dvi\Support\Acl\Model\ActionTypes;
use Dvi\Support\Acl\Model\Modules;
use Dvi\Support\Acl\Model\UserActions;
use Dvi\Support\Acl\Model\UserGroups;
use FontLib\Table\Type\name;

/**
 *  AclPermission
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
abstract class Permission
{
    public function __call($method, $arguments)
    {
        $action = $this->prepareMethodName($method);

        if ($this->methodSpecificExists($action, $arguments)) {
            return call_user_func_array([$this, $action], $arguments);
        }

        $user_id = $arguments[0];

        if ($this->hasPermissionGroup($action, $user_id)) {
            return true;
        }

        if ($this->hasUserPermission($action, $user_id)) {
            return true;
        }

        return false;
    }

    protected function prepareMethodName($name): string
    {
        $array_undescore = explode('_', $name);

        if (count($array_undescore) > 1) {
            $name = '';
            foreach ($array_undescore as $item) {
                $name .= ucfirst($item);
            }
            return $name = 'can'.$name; //ex. canCreate
        }
        return $name;
    }

    protected function methodSpecificExists($method_name, $arguments)
    {
        if (method_exists($this, $method_name)) {
           return true;
        }
        return false;
    }

    protected function hasPermissionGroup($name, $user_id): bool
    {
        $group_result = UserGroups::db('aug')
            ->select(['aug.user_id', 'ag.name', 'am.name', 'aat.name as action'])
            ->join(Groups::TABLENAME.' as ag', 'aug.group_id', '=', 'ag.id')
            ->join(GroupActions::TABLENAME.' as aga', 'ag.id', '=', 'aga.group_id')
            ->join(ActionTypes::TABLENAME.' as aat', 'aga.action_id', '=', 'aat.id')
            ->join(Modules::TABLENAME.' as am', 'aat.module_id', '=', 'am.id')
            ->where('aug.user_id', '=', $user_id)
            ->where('aat.name', '=', $name)
            ->toSql();
        return $group_result > 0 ? true : false;
    }

    protected function hasUserPermission($name, $user_id): bool
    {
        $result = UserActions::db('aup')
            ->leftJoin(ActionTypes::TABLENAME . ' as auat', 'aup.action_id', '=', 'auat.id')
            ->where('aup.user_id', '=', $user_id)
            ->where('auat.name', '=', $name)
            ->count();

        return $result > 0 ? true : false;
    }
}
