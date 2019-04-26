<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Widget\Form\TDate;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormField;
use Dvi\Support\Service\Layout\Form\Field\Facade\FieldWithMask;

/**
 * FormFieldDate
 * @property TDate $field
 */
class FormFieldDate extends FormField
{
    use FieldWithMask;

    public function databaseMask($mask)
    {
        $this->field->setDatabaseMask($mask);
        return $this;
    }
}
