<?php

namespace modules\site\models;

use yii\base\Model;

class ImageOptions extends Model
{
    public ?string $imgClass = '';
    public array $dataAttributes = [];
    /**
     * if the image should include lazy loading
     *
     * @var bool $lazyloading
     */
    protected bool $lazyloading = false;

    /**
     * sizes string for the image
     *
     * @var string|null $sizes
     */
    protected string|null $sizes = null;
    /**
     * srcset for the sources
     *
     * @var array|null $srcset
     */
    protected array|null $srcset = null;
    /**
     * placeholder URL that is rendered in case lazyloading is true
     *
     * @var string|null $placeholderUrl
     */
    protected string|null $placeholderUrl = null;
    /**
     * @var string|null $alt tag for the image
     */
    protected string|null $alt = null;
    /**
     * @var bool $useWebP
     */
    protected bool $useWebP = false;
    /**
     * additional attributes for the image tag
     *
     * @var array $imageAttributes
     */
    protected array $imageAttributes = [];
    /**
     * additional attributes for the picture tag
     *
     * @var array $pictureAttributes
     */
    protected array $pictureAttributes = [];
    /**
     * additional attributes for the source tags
     *
     * @var string[][] $sourceAttributes
     */
    protected array $sourceAttributes = [];
    /**
     * in case lazy loading is on -> check if it's the first image -> do not lazy load it
     *
     * @var bool $disableLazyLoadIfFirst
     */
    protected bool $disableLazyLoadIfFirst = false;
    /**
     * the transform parameter that is passed to $asset->getUrl()
     *
     * @var array|string|null $transform
     * @see \craft\elements\Asset::getUrl()
     */
    protected array|string|null $transform = null;

    public function isLazyloading(): bool
    {
        return $this->lazyloading;
    }

    public function setLazyloading(bool $lazyloading): ImageOptions
    {
        $this->lazyloading = $lazyloading;

        return $this;
    }

    public function getSizes(): ?string
    {
        return $this->sizes;
    }

    public function setSizes(?string $sizes): ImageOptions
    {
        $this->sizes = $sizes;

        return $this;
    }

    public function getSrcset(): ?array
    {
        return $this->srcset;
    }

    public function setSrcset(?array $srcset): ImageOptions
    {
        $this->srcset = $srcset;

        return $this;
    }

    public function getPlaceholderUrl(): ?string
    {
        return $this->placeholderUrl;
    }

    public function setPlaceholderUrl(?string $placeholderUrl): ImageOptions
    {
        $this->placeholderUrl = $placeholderUrl;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): ImageOptions
    {
        $this->alt = $alt;

        return $this;
    }

    public function isUseWebP(): bool
    {
        return $this->useWebP;
    }

    public function setUseWebP(bool $useWebP): ImageOptions
    {
        $this->useWebP = $useWebP;

        return $this;
    }

    public function getImageAttributes(): array
    {
        return $this->imageAttributes;
    }

    public function setImageAttributes(array $imageAttributes): ImageOptions
    {
        $this->imageAttributes = $imageAttributes;

        return $this;
    }

    public function getSourceAttributes(): array
    {
        return $this->sourceAttributes;
    }

    public function setSourceAttributes(array $sourceAttributes): ImageOptions
    {
        $this->sourceAttributes = $sourceAttributes;

        return $this;
    }

    public function isDisableLazyLoadIfFirst(): bool
    {
        return $this->disableLazyLoadIfFirst;
    }

    public function setDisableLazyLoadIfFirst(bool $disableLazyLoadIfFirst): ImageOptions
    {
        $this->disableLazyLoadIfFirst = $disableLazyLoadIfFirst;

        return $this;
    }

    public function getTransform(): array|string|null
    {
        return $this->transform;
    }

    public function setTransform(array|string|null $transform): ImageOptions
    {
        $this->transform = $transform;

        return $this;
    }

    public function getPictureAttributes(): array
    {
        return $this->pictureAttributes;
    }

    public function setPictureAttributes(array $pictureAttributes): ImageOptions
    {
        $this->pictureAttributes = $pictureAttributes;

        return $this;
    }
}