<?php

namespace modules\site\helpers;

use craft\elements\Asset;
use modules\site\models\ImageOptions;

class AssetHelper
{
    public static function transformWithFormat(mixed $transform, string $format = null): mixed
    {
        if ($format === null) {
            return $transform;
        }

        if (is_string($transform)) {
            $transform = \Craft::$app->getImageTransforms()->getTransformByHandle($transform);
            $transform->format = $format;

            return $transform;
        }

        if ($transform === null) {
            $transform = [];
        }

        if (!array_key_exists('format', $transform)) {
            $transform['format'] = $format;
        }

        return $transform;
    }

    public static function isSvg(Asset $asset): bool
    {
        return $asset->getMimeType() === 'image/svg+xml';
    }

    public static function getImageTransform(Asset $asset, ImageOptions $options)
    {
        if (self::isSvg($asset)) {
            return null;
        }

        return $options->getTransform();
    }
}