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
        if (YII_ENV === 'dev') {
            $this->css[] = 'assets/inertia/css/app.css';
            $this->js[] = 'assets/inertia/js/app.js';
        } else {
            $this->css[] = 'assets/inertia/css/app.min.css';
            $this->js[] = 'assets/inertia/js/app.min.js';
        }
    }
}
