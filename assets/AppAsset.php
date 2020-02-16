<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public function init()
    {
        parent::init();
        $this->css[] = YII_ENV === 'dev' ? 'css/app.css' : 'css/app.min.css';
        $this->js[] = YII_ENV === 'dev' ? 'js/app.js' : 'js/app.min.js';
    }
}
