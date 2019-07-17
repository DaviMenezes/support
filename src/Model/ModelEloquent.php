<?php

namespace Dvi\Support\Model;

use Illuminate\Database\Eloquent\Model;

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
class ModelEloquent extends Model
{
    protected $uniques = [];
    private $unique_errors = [];
    private $already_validated;
    private $result_validate;

    public $timestamps = false;

    public function getUniqueErrors()
    {
        return $this->unique_errors;
    }

    public function getUniqueErrorsStr()
    {
        $errors = $this->getUniqueErrors();
        $msg = '';
        foreach ($errors as $key => $error) {
            $msg .= $error;
            if ($key + 1 < count($errors)) {
                $msg .= '<br>';
            }
        }
        return $msg;
    }

    public function save(array $options = [])
    {
        $this->validate();

        return parent::save();
    }

    public function validate()
    {
        if ($this->already_validated) {
            return $this->result_validate;
        }
        $this->result_validate = $this->validateUnique();

        $this->already_validated = true;
        return $this->result_validate;
    }

    protected function validateUnique(): bool
    {
        foreach ($this->uniques as $field) {
            if (empty($this->$field)) {
                continue;
            }
            $obj = self::where($field, '=', $this->$field)->first();
            if ($obj) {
                $this->unique_errors[] = 'O campo <b>' . $field . '</b> é único e o registro ' . $obj->id . ' já possui o valor: '.$this->$field;
                return false;
            }
        }
        return true;
    }
}