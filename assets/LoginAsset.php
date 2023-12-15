<?php

namespace app\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot/homepage';
    public $baseUrl = '@link/homepage';

    public $css = [
        "css/login-style.css",
    ];
    public $js = [
        "js/login-script.js"
    ];

    public $depends = [
        // '\app\assets\AdminLtePluginAsset',
        // '\app\assets\AnnexPluginAsset',
    ];
}
