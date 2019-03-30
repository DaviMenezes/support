<?php

namespace Dvi\Support\Acl\Service;

use App\Model\Dvi\Base\ModelEloquent;
use Dvi\Support\Acl\Model\GenericEloquentModel;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 *  EloquentTrait
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
trait EloquentTrait
{
    /**@var Model*/
    protected $instance;

    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->orm()->$name($arguments);
        }
    }

    protected function orm()
    {
        $class = get_called_class();

        $this->createInstance();

        $this->instance->fillable(new $class());
        $this->instance->getFillable();
        $this->instance->setTable($class::TABLENAME);

        return $this->instance;
    }

    /**@return Builder*/
    public static function db()
    {
        $class = get_called_class();
        return DB::table($class::TABLENAME);
    }

    protected function createInstance(): GenericEloquentModel
    {
        return $this->instance = $this->instance ?? new GenericEloquentModel();
    }
}
