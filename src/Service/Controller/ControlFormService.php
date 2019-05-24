<?php
namespace Dvi\Support\Service\Controller;

trait ControlFormService
{
    protected function recording(): bool
    {
        if (!self::editing()) {
            return true;
        }
        return false;
    }

    protected static function editing(): bool
    {
        if (!empty(http()->url('method'))
            and in_array(http()->url('method'), ['onEdit', 'onSave'])
            and (!empty(http()->url('id')) or !empty(http()->url('key')))) {
            return true;
        }
        return false;
    }
}
