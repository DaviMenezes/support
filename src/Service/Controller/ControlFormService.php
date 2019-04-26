<?php
namespace Dvi\Support\Service\Controller;

trait ControlFormService
{
    protected function recording(): bool
    {
        if (!$this->editing()) {
            return true;
        }
        return false;
    }

    protected function editing(): bool
    {
        if (!empty($this->param['method'])
            and in_array($this->param['method'], ['onEdit', 'onSave'])
            and (!empty($this->param['id']) or !empty($this->param['key']))) {
            return true;
        }
        return false;
    }
}
