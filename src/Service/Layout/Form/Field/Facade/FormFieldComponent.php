<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Dvi\Support\Service\Layout\Form\Field\Facade\FormFieldFacade;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormFieldVarchar;

/**
 * FormFieldComponent
 *
 * @author Davi Menezes
 */
class FormFieldComponent
{
    public static function phone($name, $label = null):FormFieldVarchar
    {
        $field = FormFieldFacade::varchar($name, $label);
        $field->mask('(99)9999-9999');
        return $field;
    }

    public static function phoneFull($name, $label = null):FormFieldVarchar
    {
        $field = FormFieldFacade::varchar($name, $label);
        $field->mask('999999999');
        return $field;
    }

    public static function cellPhone($name, $label = null):FormFieldVarchar
    {
        $field = FormFieldFacade::varchar($name, $label);
        $field->mask('(99)99999-9999');
        return $field;
    }
}
