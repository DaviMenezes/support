<?php

namespace Dvi\Support\Service\Layout\Form\Field\Facade;

/**
 * FieldWithMask
 */
trait FieldWithMask
{
    public function mask(string $mask)
    {
        $this->field->setMask($mask);
        return $this;
    }
}
