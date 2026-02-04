<?php

/**
 * Twig variable for the Site module.
 *
 * Exposes small utilities and helpers for use inside Twig templates. At the
 * moment, it provides a convenience method to register the module's
 * `SiteAssetBundle` on the current Craft view so that required CSS/JS are
 * included in rendered pages.
 *
 * Example (Twig):
 *
 * ```twig
 * {# Ensure Site asset bundle is included #}
 * {{ site.includeAssetBundle() }}
 * ```
 *
 * @package modules\site\web\twig
 * @since 2025-11-28
 */

namespace modules\site\web\twig;

use Craft;
use craft\elements\Asset;
use craft\base\ElementInterface;
use modules\site\base\ImageLoader;
use modules\site\models\ImageOptions;
use modules\site\web\assets\site\SiteAssetBundle;

/**
 * Site Twig variable.
 *
 * Intended to be exposed as `site` in Twig templates. Provides helper methods
 * related to rendering on the front-end, such as registering the module's
 * asset bundle.
 */
class Variable
{
    /**
     * The element used as the index context for certain views, if any.
     *
     * @var ElementInterface|null
     */
    private ElementInterface|null $indexElement = null;

    /**
     * Optional element that represents the start of a breadcrumb trail.
     *
     * @var ElementInterface|null
     */
    protected ElementInterface|null $breadcrumbIndex = null;

    /**
     * Cache of single-type elements keyed by their handle for quick lookup.
     *
     * @var array<string, ElementInterface>
     */
    private array $singlesByHandle = [];

    public function __construct(
        public ImageLoader $imageLoader,
    )
    {
    }

    public function getIndexElement(): ?ElementInterface
    {
        return $this->indexElement;
    }

    public function getImageSources(Asset $asset, ImageOptions $options): array
    {
        return $this->imageLoader->getImageSources($asset, $options);
    }

    public function getImageAttributes(Asset $asset, ImageOptions $options): array
    {
        return $this->imageLoader->getImageAttributes($asset, $options);
    }
    
    /**
     * Register the module's Site asset bundle on the current view.
     *
     * This ensures that the CSS/JS provided by `SiteAssetBundle` are included
     * on the page being rendered.
     *
     * Example (Twig):
     *
     * ```twig
     * {{ site.includeAssetBundle() }}
     * ```
     *
     * @return void
     */
    public function includeAssetBundle(): void
    {
        $view = Craft::$app->getView();
        /** @var SiteAssetBundle $bundle */
        $view->registerAssetBundle(SiteAssetBundle::class);
    }

    public function getMenuLinks(Entry $entry): array
    {
        $links = [];
        foreach ($entry->contentMatrix as $block) {
            if (($type = $block->getType()) !== null && $type->handle === 'menuLink' && $block->showInMenu === true) {
                $slug = ElementHelper::generateSlug($block->headline);
                $links[] = [
                    'html' => $block->headline,
                    'href' => (strpos($slug, '#') !== 0) ? ('#' . $slug) : $slug,
                ];
            }
        }

        return $links;
    }
}