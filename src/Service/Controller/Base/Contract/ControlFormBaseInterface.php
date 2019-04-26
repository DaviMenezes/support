<?php
namespace Dvi\Support\Service\Base\Contract;

use Dvi\Support\Http\Request;

interface ControlFormBaseInterface
{
    public function onSave(Request $request);
}