<?php

namespace modules\site\helpers;

class Html extends \craft\helpers\Html
{
    public static function setDataAttribute(array &$attributes, string $key, mixed $value)
    {
        if (!isset($attributes['data']) || !is_array($attributes['data'])) {
            $attributes['data'] = [];
        }

        $attributes['data'][$key] = $value;
    }
}