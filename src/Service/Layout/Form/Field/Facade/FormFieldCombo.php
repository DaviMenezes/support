<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TRecord;
use Adianti\Database\TRepository;
use Adianti\Widget\Form\TCombo;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormFieldItems;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormField;

/**
 * FormFieldCombo
 * @property TCombo $field
 * @property TRecord $model
 * @method TCombo get()
 */
class FormFieldCombo extends FormFieldItems
{
    public function searchable($value = true)
    {
        if ($value == true) {
            $this->field->enableSearch();
        }
        return $this;
    }

    public function defaultOption(bool $default = false)
    {
        $this->field->setDefaultOption($default);
        return $this;
    }

    public function changeAction($action)
    {
        $this->field->setChangeAction($action);
        return $this;
    }
}
