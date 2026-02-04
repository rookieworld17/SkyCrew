<?php

/**
 * Skycrew custom Craft CMS module bootstrap.
 *
 * This module sets up controller namespaces for web/console contexts,
 * configures i18n message sources, and registers Control Panel template roots
 * for any templates that live under this module's `templates` directory.
 *
 * @package   modules
 */

namespace modules;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;
use yii\base\Event;

/**
 * Module entry point for the Skycrew project.
 *
 * Extends Yii's base `Module` to provide Craft-specific initialization:
 * - Stores a static `instance` reference for convenience
 * - Sets path aliases for `@modules/<id>` and `@/<id>`
 * - Determines the appropriate controller namespace (console vs web)
 * - Registers an i18n message source under the module ID
 * - Adds the module `templates` folder to CP template roots (if present)
 */
class Module extends \yii\base\Module
{
    /**
     * The singleton-like reference to this module instance.
     *
     * Note: Craft modules commonly expose a static `$instance` for convenient
     * access, mirroring how plugins provide a global instance.
     *
     * @var self|null
     */
    public static $instance;

    /**
     * Module constructor.
     *
     * Performs core initialization and environment-specific configuration.
     *
     * @param string                 $id      The module ID.
     * @param \yii\base\Module|null $parent  The parent module, if any.
     * @param array<string,mixed>    $config  Module configuration.
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        self::$instance = $this;

        static::setInstance($this);

        Craft::setAlias('@modules/' . $id, $this->getBasePath());
        Craft::setAlias('@/' . $id, $this->getBasePath());

        $this->controllerNamespace = null;
        if ($this->controllerNamespace === null && ($pos = strrpos(static::class, '\\')) !== false) {
            $namespace = substr(static::class, 0, $pos);

            if (Craft::$app->getRequest()->getIsConsoleRequest()) {
                if (file_exists($this->getBasePath() . DIRECTORY_SEPARATOR . 'console')) {
                    $this->controllerNamespace = $namespace . '\\console\\controllers';
                }
            } elseif(file_exists($this->getBasePath() . DIRECTORY_SEPARATOR . 'controllers')) {
                $this->controllerNamespace = $namespace . '\\controllers';
            }
        }

        $i18n = Craft::$app->getI18n();
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id . '*'])) {
            $i18n->translations[$id] = [
                'class'            => PhpMessageSource::class,
                'sourceLanguage'   => 'en-US',
                'basePath'         => '@' . $id . '/translations',
                'forceTranslation' => true,
                'allowOverrides'   => true,
            ];
        }

        Event::on(
            View::class,
            View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
            function(RegisterTemplateRootsEvent $e) {
                if (is_dir($baseDir = $this->getBasePath() . DIRECTORY_SEPARATOR . 'templates')) {
                    $e->roots[$this->id] = $baseDir;
                }
            }
        );

        parent::__construct($id, $parent, $config);
    }

    /**
     * Resolves the controller path alias for the module.
     *
     * If a controller namespace was determined during construction, returns
     * the aliased filesystem path to that namespace. Otherwise returns an
     * empty string to indicate no controller path.
     *
     * @return string The controller path alias or an empty string.
     */
    public function getControllerPath()
    {
        if ($this->controllerNamespace === null) {
            return '';
        }

        return Craft::getAlias('@' . str_replace('\\', '/', $this->controllerNamespace));
    }
}