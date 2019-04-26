<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Widget\Form\TEntry;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormField;
use Dvi\Support\Service\Layout\Form\Field\Facade\FieldWithMask;

/**
 * FormFieldVarchar
 * Metodos fluentes para campos varchar
 * @property TEntry $field
 * @method TEntry get()
 */
class FormFieldVarchar extends \Dvi\Support\Service\Layout\Form\Field\Facade\FormField
{
    use FieldWithMask;

    public function uppercase()
    {
        $this->field->forceUpperCase();
        return $this;
    }

    public function maxlength($length)
    {
        $this->field->setMaxLength($length);
        return $this;
    }
}
