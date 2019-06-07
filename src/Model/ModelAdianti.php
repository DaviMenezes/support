<?php

namespace Dvi\Support\Model;

use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Database\TRecord;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\Builder;
use ReflectionException;

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
    const TABLENAME = '';
    const PRIMARYKEY = 'id';
    const IDPOLICY = 'serial';

    protected $fillable = array();

    protected $uniques = [];
    private $unique_errors = [];
    private $already_validated;
    private $result_validate;
    protected $requireds = [];
    protected $required_errors = [];
    protected $another_errors;

    public function __construct($id = null, bool $callObjectLoad = true)
    {
        parent::__construct($id, $callObjectLoad);

        $this->addAttributes();

        $this->addFillable();
    }

    public function addFillable()
    {
        $this->fillable = $this->getAttributes();
    }

    protected function addAttributes()
    {
        try {
            $props = props(get_called_class());
            foreach ($props as $property) {
                parent::addAttribute($property);
            }
        } catch (ReflectionException $e) {
            throw new Exception('Preparando atributos do modelo ' . self::class . ': ' . $e->getMessage());
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
            foreach ($this->another_errors as $another_error) {
                $errors[] = ['type' => 'error', 'msg' => $another_error];
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
                $operator = $this->getOperator($current_field);
                $value = $this->getFielterValue($current_field);
                $obj = self::where($group_fields[0], $operator, $value);
                foreach ($group_fields as $key => $current_field) {
                    if ($key == 0) {
                        continue;
                    }
                    $operator = $this->getOperator($current_field);
                    $value = $this->getFielterValue($current_field);
                    $obj->where($current_field, $operator, $value);
                }
                $count = $obj->count();

                if ($count > 0) {
                    $fields = implode($group_fields, ', ');

                    $this->unique_errors[] = 'O grupo de campos <b>' . $fields . '</b> é único e o registro ' . $obj->first()->id . ' já possui os valores informados';
                    return false;
                }
            } else {
                $current_field = $field;
                $obj = self::where($field, '=', $this->$field);
                $count = $obj->count();

                if ($count > 0) {
                    $this->unique_errors[] = 'O campo <b>' . $current_field . '</b> é único e o registro ' . $obj->first()->id . ' já possui o valor: ' . $this->$current_field;
                    return false;
                }
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
                $this->required_errors[] = 'O campo ' . $attribute . ' é obrigatório e o valor "null" é inválido';
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

    /**@return Builder */
    public static function db($alias = null)
    {
        $class = get_called_class();
        $table = $class::TABLENAME;

        $alias = !$alias ? '' : ' as ' . $alias;
        return DB::table($table . $alias);
    }

    /**@return ModelEloquent */
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

    protected function getOperator($current_field): string
    {
        $operator = isEmpty($this->$current_field) ? 'is' : '=';
        return $operator;
    }

    protected function getFielterValue($current_field)
    {
        $value = isEmpty($this->$current_field) ? null : $this->$current_field;
        return $value;
    }

    public function fill($data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    public function __get($property)
    {
        $property = str_replace(['(', ')'], '', $property);
        if (isset($this->data[$property])) {
            return $this->data[$property];
        } elseif (isset($this->vdata[$property])) {
            return $this->vdata[$property];
        }

        if (method_exists($this, $property)) {
            // execute the method get_<property>
            return call_user_func(array($this, $property));
        }
        if (method_exists($this, 'get_'.$property)) {
            return call_user_func(array($this, 'get_'.$property));
        }
        if (strpos($property, '->') !== false) {
            $parts = explode('->', $property);
            $container = $this;
            $result = null;
            foreach ($parts as $part) {
                if (is_object($container)) {
                    $result = $container->$part;
                    $container = $result;
                } else {
                    throw new Exception(AdiantiCoreTranslator::translate('Trying to access a non-existent property (^1)', $property));
                }
            }
            return $result;
        }
    }
}
