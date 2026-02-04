<?php
namespace modules\site\web\twig;

use craft\elements\Entry;
use modules\site\models\ImageOptions;
use craft\helpers\ArrayHelper;
use craft\helpers\FileHelper;
use modules\site\helpers\ImageTransformHelper;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;
use Twig\TwigFilter;

class Extension extends AbstractExtension implements GlobalsInterface
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('lastModifiedTime', [FileHelper::class, 'lastModifiedTime']),
        ];
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('eagerLoadPage', [$this, 'eagerLoadPage']),
            new TwigFunction('getImageOptions', [$this, 'getImageOptions']),
        ];
    }

    public function getImageOptions(array $options = [], array $defaults = []): ImageOptions
    {
        $parameters = ArrayHelper::merge($defaults, $options);

        if (isset($parameters['class'])) {
            $parameters['imgClass'] = $parameters['class'];
            unset($parameters['class']);
        }

        $parameters['class'] = ImageOptions::class;;

        return \Craft::createObject($parameters);
    }

    public function eagerLoadPage(Entry $entry): void
    {
        \Craft::$app->getElements()->eagerLoadElements(
            $entry::class,
            [$entry],
            [
                ['contentMatrix.imageModule:image'],
                ['contentMatrix.videoModule:video'],
                ['contentMatrix.imageModule:image'],
                [
                    'contentMatrix.galleryModule:images',
                    [
                        'withTransforms' => ImageTransformHelper::getTransforms([
                                [
                                    'width' => 500,
                                    'height' => 500
                                ]
                            ]
                        )
                    ]
                ],
            ]
        );
    }

    public function getGlobals(): array
    {
        return [];
    }
}