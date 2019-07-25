<?php

namespace Dvi\Config;

use eftec\bladeone\BladeOne;

class Config
{
    public static function get()
    {
        return [
            'template_engines' => [
                'blade_onde' => [
                    'view_path' => '',
                    'view_cache_path' => '',
                    'blade_mode' => BladeOne::MODE_AUTO
                ]
            ]
        ];
    }
}