<?php

namespace modules\site\web\assets;

use Craft;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use craft\web\AssetBundle;
use craft\web\View;
use modules\site\base\Translations;
use modules\site\helpers\RequestHelper;
use yii\base\InvalidConfigException;
use yii\web\Request;
use yii\web\User;

class BaseAssetBundle extends AssetBundle
{
    public string $jsEntryPoint;

    public function __construct(protected Translations $translations, array $config = [])
    {
        parent::__construct($config);
    }

    public function init(): void
    {
        if (empty($this->jsEntryPoint)) {
            throw new InvalidConfigException('jsEntryPoint must be set');
        }

        $this->sourcePath = '@modules/site/web/assets/site/';
        $pathJs = [
            ['js/site.js', 'type' => 'module'],
            ['js/site.umd.cjs', 'nomodule' => ''],
        ];
        $this->css = [
            'js/site.css'
        ];
        if (getenv('CRAFT_ENVIRONMENT') !== 'production') {
            $devServer = RequestHelper::getDevServer();

            if ($devServer) {
                $pathJs = [
                    $devServer . $this->jsEntryPoint,
                    $devServer . '@vite/client'
                ];
                $this->jsOptions = [
                    'type' => 'module'
                ];
                $this->css = [];
            }
        }
        $this->js = $pathJs;

        parent::init();
    }
}