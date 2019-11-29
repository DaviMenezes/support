<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

/**
 * @author     Davi Menezes
 * @copyright  Copyright (c) 2019. (davimenezes.dev@gmail.com)
 * @see https://github.com/DaviMenezes
 */
class FormFieldDateTime extends FormField
{
    use FieldWithMask;

    public function databaseMask($mask)
    {
        $this->field->setDatabaseMask($mask);
        return $this;
    }
}
