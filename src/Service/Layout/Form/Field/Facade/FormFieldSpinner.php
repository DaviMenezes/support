<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Widget\Form\TSpinner;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormFieldItems;

/**
 * FieldSpinner
 * @property TSpinner $field
 * @method TSpinner get()
 */
class FormFieldSpinner extends \Dvi\Support\Service\Layout\Form\Field\Facade\FormFieldItems
{
    public function range(int $min, int $max, int $step)
    {
        $this->field->setRange($min, $max, $step);
        return $this;
    }
}
