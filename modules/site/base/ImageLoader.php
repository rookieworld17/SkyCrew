<?php

namespace modules\site\base;

use craft\elements\Asset;
use craft\helpers\Html;
use modules\site\helpers\AssetHelper;
use modules\site\models\ImageOptions;
use yii\base\Component;

class ImageLoader extends Component
{
    protected bool $isFirstLazyLoadImage = true;

    public function getImageSources(Asset $asset, ImageOptions $options): array
    {
        $sources = [];
        if(AssetHelper::isSvg($asset)){
            return [];
        }

        $doLazyLoad = $this->isLazyLoading($options);
        $prefix = $doLazyLoad ? 'data-' : '';
        if ($options->isUseWebP()) {
            $sources[] = [
                'type'   => 'image/webp',
                'format' => 'webp',
            ];
        }

        $sources[] = [
            'type'   => $asset->getMimeType(),
            'format' => null,
        ];

        $sizes = $options->getSizes();
        foreach ($sources as $key => $source) {
            if (!empty($sizes)) {
                $sources[$key][$prefix . 'sizes'] = $sizes;
            }
            if (!empty($options->getSrcset())) {
                $format = $source['format'] ?? null;
                $transform = $options->getTransform();
                if($format){
                    if(is_string($transform)){
                        /** @var \craft\models\ImageTransform $transform */
                        $transform = \Craft::$app->getImageTransforms()->getTransformByHandle($transform);
                        $transform->format = $format;
                    } else {
                        $transform['format'] = $format;
                    }
                }

                $sources[$key][$prefix . 'srcset'] = $asset->getSrcset($options->getSrcset(), $transform);
            }

            $additionalAttributes = $options->getSourceAttributes();
            if(array_key_exists($key, $additionalAttributes)){
                $additionalAttributes = $additionalAttributes[$key];
            }

            foreach ($additionalAttributes as $attribute => $value) {
                $sources[$key][(string)$attribute] = $value;
            }

            unset($sources[$key]['format']);
        }


        if ($options->isLazyloading()) {
            $this->isFirstLazyLoadImage = false;
        }

        return $sources;
    }

    public function getImageAttributes(Asset $asset, ImageOptions $options): array
    {
        $attributes = [];
        $doLazyLoad = $this->isLazyLoading($options);

        $url = $asset->getUrl(AssetHelper::getImageTransform($asset, $options));
        foreach ($options->getImageAttributes() as $key => $value) {
            $attributes[$key] = $value;
        }

        if ($doLazyLoad) {
            $classes = Html::explodeClass(($attributes['class'] ?? []));
            $classes[] = 'lazyload';
            $attributes['class'] = $classes;

            \modules\site\helpers\Html::setDataAttribute($attributes, 'src', $url);
            $attributes['src'] = $options->getPlaceholderUrl();
        } else {
            $attributes['src'] = $url;
        }

        if (!isset($attributes['title'])) {
            $attributes['title'] = $asset->title;
        }

        // no alt attribute set yet? set the image title
        if (!isset($attributes['alt'])) {
            $attributes['alt'] = $asset->alt ?? '';
        }

        if (!isset($attributes['class']) && $options->imgClass) {
            $attributes['class'] = $options->imgClass;
        }

        // focal point
//        $focalPoint = $asset->getFocalPoint();
//        if ($focalPoint) {
//            $x = $focalPoint['x'] * 100;
//            $y = $focalPoint['y'] * 100;
//            $style = $attributes['style'] ?? null;
//
//            $newStyle = [
//                '--focusX' => $x . '%',
//                '--focusY' => $y . '%'
//            ];
//
//            if ($style === null) {
//                // just set it
//                $attributes['style'] = $newStyle;
//            } elseif (is_array($style)) {
//                // merge with existing
//                $attributes['style'] = array_merge($style, $newStyle);
//            } elseif (is_string($style)) {
//                // they passed it as string
//                $style = Html::explodeStyle($style);
//                $attributes['style'] = array_merge($style, $newStyle);
//            }
//        }

        return $attributes;
    }

    public function isLazyLoading(ImageOptions $options): bool
    {
        if (!$options->isLazyloading()) {
            return false;
        }

        if ($this->isFirstLazyLoadImage && $options->isDisableLazyLoadIfFirst()) {
            return false;
        }

        return true;
    }
}