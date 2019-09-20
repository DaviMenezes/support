<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Base\Lib\Widget\Form\TFile;
use Adianti\Base\Lib\Widget\Form\THidden;
use Adianti\Base\Lib\Widget\Form\THtmlEditor;
use Adianti\Base\Lib\Widget\Form\TText;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TNumeric;
use Adianti\Widget\Form\TRadioGroup;
use Adianti\Widget\Form\TSpinner;
//use Adianti\Widget\Form\TText;
use Dvi\Adianti\Widget\Form\Field\Hidden;
use Dvi\Adianti\Widget\Form\Field\Text;
use Dvi\Adianti\Widget\Form\Field\UniqueSearch;
use Dvi\Component\Widget\Form\Field\Combo\Combo;
use Dvi\Component\Widget\Form\Field\Varchar;

//use Adianti\Widget\Form\THidden;

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

    public static function hidden($name)
    {
        $formField = new FormField(new Hidden($name));

        return $formField;
    }

    public static function text(string $name, $label = null)
    {
        $formField = new FormField(new Text($name, $label), $label);

        return $formField;
    }

    public static function numeric(string $name, $decimal, $decimal_separator, $thousandSeparator, $replaceOnPost = true, $label = null)
    {
        $formField = new FormField(new TNumeric($name, $decimal, $decimal_separator, $thousandSeparator, $replaceOnPost), $label);

        return $formField;
    }

    public static function combo(string $name, $label = null)
    {
        $formField = new FormFieldCombo(new Combo($name, $label), $label);

        return $formField;
    }

    public static function html($name, $label = null)
    {
        $field = new FormField(new THtmlEditor($name), $label);

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
