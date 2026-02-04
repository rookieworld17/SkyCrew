<?php

namespace modules\site\transformer;

use craft\elements\Asset;
use League\Fractal\TransformerAbstract;
use modules\site\base\ImageLoader;
use modules\site\models\ImageOptions;

class ImageTransformer extends TransformerAbstract
{
    public function __construct(protected ImageOptions $imageOptions, protected ImageLoader $imageLoader)
    {
    }

    public function transform(Asset $asset): array
    {
        return [
            'sources'           => $this->imageLoader->getImageSources($asset, $this->imageOptions),
            'imageAttributes'   => $this->imageLoader->getImageAttributes($asset, $this->imageOptions),
            'pictureAttributes' => $this->imageOptions->getPictureAttributes(),
        ];
    }
}