<?php

namespace Dvi\Support\Model;

use Adianti\Database\TRecord;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\Builder;

/**
 *  ControlLoadService
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2018. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @see https://t.me/davimenezes
 */
class ModelAdianti extends TRecord
{
    const TABLENAME =  '';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial';

    protected $fillable = array();

    protected $uniques = [];
    private $unique_errors = [];
    private $already_validated;
    private $result_validate;
    protected $requireds = [];
    protected $required_errors = [];

    public function __construct($id = null, bool $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);

        $this->addAttributes();
    }

    protected function addAttributes()
    {
        foreach ($this->fillable as $property) {
            parent::addAttribute($property);
        }
    }

    public function validate()
    {
        if ($this->already_validated) {
            return $this->result_validate;
        }
        $this->result_validate = $this->validateUnique();

        $this->validateRequireds();

        $this->already_validated = true;
        return $this->result_validate;
    }

    public function getUniqueErrors()
    {
        return $this->unique_errors ?? [];
    }

    public function getUniqueErrorsStr()
    {
        $errors = $this->getUniqueErrors();
        $msg = '';
        foreach ($errors as $key => $error) {
            $msg += $error;
            if ($key + 1 < count($errors)) {
                $msg += '<br>';
            }
        }
        return $msg;
    }

    public function getRequiredErrorsStr()
    {
        $errors = $this->getRequiredErrors();
        $msg = '';
        foreach ($errors as $key => $error) {
            $msg += $error;
            if ($key + 1 < count($errors)) {
                $msg += '<br>';
            }
        }
        return $msg;
    }

    public function getErrors()
    {
        $unique_errors = $this->getUniqueErrors();
        $required_errors = $this->getRequiredErrors();

        $errors = [];
        if (count($unique_errors) or count($required_errors)) {
            foreach ($unique_errors as $unique_error) {
                $errors[] = ['type' => 'error', 'msg' => $unique_error];
            }
            foreach ($required_errors as $required_error) {
                $errors[] = ['type' => 'error', 'msg' => $required_error];
            }
        }

        return $errors;
    }

    protected function validateUnique(): bool
    {
        if (!empty($this->id)) {
            return true;
        }
        foreach ($this->uniques as $key => $field) {
            if ($key !== 'group' and empty($this->$field)) {
                continue;
            }

            if ($key == 'group' and is_array($field)) {
                $group_fields = $field;

                $current_field = $group_fields[0];
                $obj = self::where($group_fields[0], '=', $this->$current_field);
                foreach ($group_fields as $key => $current_field) {
                    if ($key == 0) {
                        continue;
                    }
                    $obj->where($current_field, '=', $this->$current_field);
                }
                $count = $obj->count();
            } else {
                $current_field = $field;
                $obj = self::where($field, '=', $this->$field);
                $count = $obj->count();
            }
            if ($count > 0) {
                $this->unique_errors[] = 'O campo <b>' . $current_field . '</b> é único e o registro ' . $obj->first()->id . ' já possui o valor: ' . $this->$current_field;
                return false;
            }
        }
        return true;
    }

    public function store()
    {
        $this->validate();

        $errors = $this->getErrors();
        if (is_array($errors) and count($errors)) {
            return null;
        }

        return parent::store();
    }

    public function save()
    {
        $this->store();
    }

    protected function validateRequireds()
    {
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            if (in_array($attribute, $this->requireds) and empty($this->$attribute)) {
                $this->required_errors[] = 'O campo '.$attribute.' é obrigatório e o valor "null" é inválido';
            }
        }
    }

    protected function getRequiredErrors()
    {
        return $this->required_errors ?? [];
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    /**@return Builder*/
    public static function db($alias = null)
    {
        $class = get_called_class();
        $table = $class::TABLENAME;

        $alias = !$alias ? '' : ' as ' . $alias;
        return DB::table($table.$alias);
    }

    /**@return ModelEloquent*/
    public static function orm($id = null)
    {
        $class = get_called_class();

        $model = new class extends ModelEloquent {
        };
        $model->fillable((new $class())->getFillable());
        $model->setTable($class::TABLENAME);
        if ($id) {
            $model->id = $id;
        }

        return $model;
    }
}

