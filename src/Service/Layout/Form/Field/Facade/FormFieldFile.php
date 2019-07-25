<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Base\Lib\Widget\Form\TFile;
use Dvi\Adianti\Widget\Form\Field\FormFieldTrait;
use Dvi\Adianti\Widget\Form\Field\FormFieldValidationTrait;

/**
 * @property TFile $field
 * @method TFile get()
 */
class FormFieldFile extends FormField
{
    use FormFieldTrait;
    use FormFieldValidationTrait;
}
