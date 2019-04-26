<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Widget\Form\TRadioGroup;
use Adianti\Widget\Form\TSpinner;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormFieldItems;

/**
 * FormFieldRadio
 * @property TRadioGroup $field
 * @method TRadioGroup get()
 */
class FormFieldRadio extends FormFieldItems
{
    public function horizontal()
    {
        $this->field->setLayout('horizontal');
        return $this;
    }

    public function vertical()
    {
        $this->field->setLayout('vertical');
        return $this;
    }

    public function asButton()
    {
        $this->field->setUseButton();
        return $this;
    }
}
