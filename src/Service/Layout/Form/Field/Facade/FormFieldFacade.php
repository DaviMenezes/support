<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Base\Lib\Widget\Form\TFile;
use Adianti\Base\Lib\Widget\Form\TRadioGroup;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TSpinner;
use Dvi\Adianti\Componente\Model\Form\Fields\Numeric;
use Dvi\Component\Widget\Form\Field\Combo\Combo;
use Dvi\Component\Widget\Form\Field\Hidden\Hidden;
use Dvi\Component\Widget\Form\Field\HtmlEditor;
use Dvi\Component\Widget\Form\Field\Text;
use Dvi\Component\Widget\Form\Field\UniqueSearch;
use Dvi\Component\Widget\Form\Field\Varchar;

/**
 * FormFieldFacade
 * Obtém campos de formulários
 * @see http://github.com/DaviMenezes
 */
class FormFieldFacade
{
    public static function varchar($name, $label = null):FormFieldVarchar
    {
        $formField = new FormFieldVarchar(new Varchar($name, $label), $label);

        return $formField;
    }

    public static function hidden($name, $class = Hidden::class)
    {
        $formField = new FormField(new $class($name));

        return $formField;
    }

    public static function text(string $name, $label = null)
    {
        $formField = new FormField(new Text($name, $label), $label);

        return $formField;
    }

    public static function numeric(
        string $name,
        int $decimal,
        $decimal_separator = ',',
        $thousandSeparator = '.',
        $label = null,
        $replaceOnPost = true
    ) {
        $formField = new FormField(new Numeric(
            $name,
            $decimal,
            $decimal_separator,
            $thousandSeparator,
            $replaceOnPost
        ), $label);

        return $formField;
    }

    public static function combo(string $name, $label = null)
    {
        $formField = new FormFieldCombo(new Combo($name, $label), $label);

        return $formField;
    }

    public static function html($name, $height, $label = null)
    {
        $field = new FormField(new HtmlEditor($name, $height, $label), $label);

        return $field;
    }

    public static function spinner(string $name, $label = null)
    {
        $formField = new FormFieldSpinner(new TSpinner($name), $label);
        $formField->range(1, 2000, 1);

        return $formField;
    }

    public static function radio(string $name, $label = null)
    {
        $formField = new FormFieldRadio(new TRadioGroup($name), $label);
        return $formField;
    }

    public static function date(string $name, $label = null)
    {
        $field = new \Dvi\Support\Service\Layout\Form\Field\Facade\FormFieldDate(new TDate($name), $label);
        return $field;
    }

    public static function uniqueSearch(UniqueSearch $field)
    {
        $formField = new FormFieldUniqueSearch($field);

        return $formField;
    }

    public static function file(string $name)
    {
        $formField = new FormField(new TFile($name));

        return $formField;
    }
}
