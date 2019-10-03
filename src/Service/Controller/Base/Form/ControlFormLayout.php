<?php

namespace Dvi\Support\Service\Controller\Base\Form;

use Adianti\Base\Lib\Wrapper\BootstrapFormBuilder;
use Adianti\Registry\TSession;
use Adianti\Validator\TRequiredValidator;
use Adianti\Widget\Form\AdiantiWidgetInterface;
use Adianti\Widget\Form\TLabel;
use Dvi\Support\Service\Layout\Form\Field\Facade\FormFieldFacade;

trait ControlFormLayout
{
    protected function createForm()
    {
        $this->form = new BootstrapFormBuilder(static::getFormName());
        $this->form->setFormTitle($this->getPageTitle());
        $this->createFormFields();

        $this->form->setCurrentPage(http()->query('tab'));

        if ($this->form->getFields()) {
            $this->form->setData(TSession::getValue(get_called_class().'_filter_data'));
        }

        $this->createFormActions();
    }

    abstract protected static function getFormName();

    protected function getFieldId()
    {
        $field = FormFieldFacade::hidden('id');
        if (notEmpty(http()->request('id'))) {
            $field = FormFieldFacade::varchar('id', 'Id')->disable()->size('20%');
        }
        $field->value(http()->query('id', static::$currentObject->id));
        return $field->get();
    }

    protected function createFormFields()
    {
        $this->createFieldId();
    }

    protected function createFormActions()
    {
        $this->createFormActionBackToList();

        $this->createFormActionSave();
    }

    protected function getField(AdiantiWidgetInterface $field, $required = false)
    {
        if ($required) {
            $field->addValidation(ucfirst($field->getName()), new TRequiredValidator());
        }
        return $field;
    }

    protected function createFieldId()
    {
        $this->form->addFields([$this->getFieldId()]);
    }

    protected function createLabelId(): array
    {
        if (!editing()) {
            return [];
        }
        return [new TLabel('Id:', null, '14px', null)];
    }

    protected function getFormData()
    {
        $data = $this->form->getData();
        $empty_form = true;

        foreach ((array)$data as $name => $value) {
            if (!empty($value)) {
                $empty_form = false;
                break;
            }
        }
        if ($empty_form) {
            $data = (object)http()->all();
            unset($data->class, $data->method);
        }
        return $data;
    }
}
