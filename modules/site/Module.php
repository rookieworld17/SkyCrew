<?php

namespace modules\site;

use Craft;
use modules\site\base\EventHandlers;
use modules\site\web\twig\Extension;
use yii\base\BootstrapInterface;

class Module extends \modules\Module implements BootstrapInterface
{
    public static $instance;

    public function init(): void
    {
        Craft::setAlias('@modules/site', $this->getBasePath());
        parent::init();
    }

    public function bootstrap($app)
    {
        (new EventHandlers())->attachEventHandler();

        $extension = Craft::createObject(Extension::class);
        $app->view->registerTwigExtension($extension);
    }
}