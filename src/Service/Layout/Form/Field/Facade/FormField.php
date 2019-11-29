<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

use Adianti\Base\Lib\Widget\Form\TField;
use Dvi\Component\Widget\Form\Field\Validator\RequiredValidator;

/**
 *  FormField
 *
 * @package
 * @subpackage
 * @author     Davi Menezes
 * @copyright  Copyright (c) (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 * @property TField $field
 */
class FormField
{
    protected $field;
    protected $label;

    public function __construct(TField $field, $label = null)
    {
        $this->field = $field;
        $field->setId($field->getName());

        if ($label) {
            $this->setLabel($label);
        }
    }

    public function required()
    {
        $this->field->addValidation($this->getLabel(), new RequiredValidator());
        $this->field->setProperty('required', 'required');
        return $this;
    }

    public function tip($tip)
    {
        $this->field->setTip($tip);
        return $this;
    }

    public function label($label)
    {
        $this->label = ucfirst($label ?? ($this->field->getName()));
        $this->field->setLabel($label);
        return $this;
    }

    public function width($widt)
    {
        $this->field->setSize($widt, null);
        return $this;
    }

    public function size($size, $height = null)
    {
        $this->field->setSize($size, $height);
        return $this;
    }

    public function value($value)
    {
        $this->field->setValue($value);
        $this->field->setProperty('value', $value);
        return $this;
    }

    public function editable(bool $editable = true)
    {
        $this->field->setEditable($editable);
        return $this;
    }

    /**@param bool $condition
     * @return FormField
     */
    public function disable($condition = null)
    {
        if (isset($condition)) {
            if ($condition == true) {
                $this->field->setEditable(false);

                $this->field->setTip('desabilitado');
            }
            return $this;
        } else {
            $this->field->setEditable(false);

            $this->field->setTip('desabilitado');
        }
        return $this;
    }

    public function property($property, $value, $replace = true)
    {
        $this->field->setProperty($property, $value, $replace);
        return $this;
    }

    public function placeholder(string $str)
    {
        $this->field->placeholder = $str;
        return $this;
    }

    public function getLabel()
    {
        return ucfirst($this->label ?? ($this->field->getName()));
    }

    private function setLabel($label)
    {
        $this->label = $label;
        $this->field->setLabel($label);
    }

    public function get()
    {
        return $this->field;
    }

    public function show()
    {
        return $this->get()->show();
    }

    public function contentLeft()
    {
        $this->contentAlign('left');
        return $this;
    }

    public function contentRight()
    {
        $this->contentAlign('right');
        return $this;
    }

    public function contentCenter()
    {
        $this->contentAlign('center');
        return $this;
    }

    public function contentAlign($position)
    {
        $this->field->style = 'text-align:'.$position;
        return $this;
    }
}
