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
        if (!empty(http()->request('id')) or !empty(http()->request('key'))) {
            return true;
        }
        return false;
    }
}
