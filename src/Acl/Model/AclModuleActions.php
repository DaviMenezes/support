<?php

namespace Dvi\Support\Acl\Model;

use Dvi\Support\Acl\Service\EloquentTrait;

/**
 *  ModuleActions
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @mixin \Eloquent
 */
class AclModuleActions
{
    public const TABLENAME = 'acl_module_actions';

    protected $fillable = ['id', 'module_id', 'action_id'];

    use EloquentTrait;
}