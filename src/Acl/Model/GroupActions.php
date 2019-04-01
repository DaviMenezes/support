<?php

namespace Dvi\Acl\Model;

use App\Model\Dvi\Base\ModelAdianti;

/**
 *  GroupActions
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @property string $id
 * @property string $group_id
 * @property string $action_id
 */
class GroupActions extends ModelAdianti
{
    public const TABLENAME = 'acl_group_actions';
    protected $fillable = ['id', 'group_id', 'action_id'];
}
