<?php

/**
 * Image transformation utilities for generating Craft CMS transform arrays.
 *
 * This file provides helper methods to expand base image transform definitions
 * into a comprehensive list that includes per-format variants (e.g., WEBP, JPG).
 *
 * @package modules\site\helpers
 * @since 2025-11-28
 */

namespace modules\site\helpers;

/**
 * Helper providing methods to generate image transform configurations with
 * additional per-format variants.
 */
class ImageTransformHelper
{
    /**
     * Build a list of transforms including one variant per requested format.
     *
     * Accepts either a single transform definition or a list of transform
     * definitions. For each base transform, the original is included and a copy
     * is added for each provided format with the `format` key set accordingly.
     *
     * Example:
     * ```php
     * ImageTransformHelper::getTransforms(['width' => 800], ['webp', 'jpg']);
     * // returns:
     * // [
     * //   ['width' => 800],
     * //   ['width' => 800, 'format' => 'webp'],
     * //   ['width' => 800, 'format' => 'jpg'],
     * // ]
     * ```
     *
     * @param array $baseTransforms A single transform definition or an array of transforms.
     * @param array $formats        Output formats to generate per base transform (default ['webp','jpg']).
     * @return array                Combined list containing original and per-format transforms.
     */
    public static function getTransforms(array $baseTransforms, array $formats = ['webp', 'jpg']): array
    {
        if (isset($baseTransforms['width']) || isset($baseTransforms['height'])) {
            $baseTransforms = [
                $baseTransforms
            ];
        }

        $transforms = [];
        foreach ($baseTransforms as $t) {
            $transforms[] = $t;
            foreach ($formats as $format) {
                $t['format'] = $format;
                $transforms[] = $t;
            }
        }

        return $transforms;
    }
}