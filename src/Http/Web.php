<?php
namespace Dvi\Support\Http;

class Web
{
    public static function services()
    {
        return [

        ];
    }

    public static function editingParameters(): array
    {
        return [
            ['key' => 'method', 'value' => 'onEdit'],
            ['key' => 'id']
        ];
    }
}
