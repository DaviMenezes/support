<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Validator\TCNPJValidator;
use Adianti\Validator\TCPFValidator;
use Adianti\Validator\TEmailValidator;
use Adianti\Widget\Form\TEntry;

/**
 * FormFieldVarchar
 * Metodos fluentes para campos varchar
 * @property TEntry $field
 * @method TEntry get()
 */
class FormFieldVarchar extends FormField
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

    public function cpf()
    {
        $this->field->addValidation($this->field->getLabel(), new TCPFValidator());
        return $this;
    }

    public function cnpj()
    {
        $this->field->addValidation($this->field->getLabel(), new TCNPJValidator());
        return $this;
    }

    public function email()
    {
        $this->field->addValidation($this->field->getLabel(), new TEmailValidator());
        return $this;
    }
}
