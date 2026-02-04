<?php

namespace modules\site\web\assets\site;

use modules\site\web\assets\BaseAssetBundle;

class SiteAssetBundle extends BaseAssetBundle
{
    public function init(): void
    {
        $this->sourcePath = "@modules/site/web/assets/site/";
        $this->jsEntryPoint = 'src/main.ts';
        parent::init();
    }
}
