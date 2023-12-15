<?php

namespace app\assets;

use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
    public $basePath = '@webroot/homepage';
    public $baseUrl = '@link/homepage';

    public $css = [
        "font/font-awesome/css/font-awesome.min.css",
        "font/mdi-font/css/material-design-iconic-font.min.css",
        "vendor/bootstrap4/bootstrap.min.css",

        // "vendor/owl-carousel/animate.css",
        // "vendor/owl-carousel/owl.carousel.min.css",
        // "vendor/owl-carousel/owl.theme.default.min.css",
        // "vendor/revolution/settings.css",
        // "vendor/revolution/navigation.css",
        // "vendor/revolution/layers.css",
        // "vendor/lightbox2/src/css/lightbox.css",
        // "css/font.css",
        "css/style.css",
        '../css/select2.min.css',
    ];
    public $js = [
        // "vendor/jquery-3.2.1.min.js",
        "vendor/wow/wow.min.js",
        "vendor/bootstrap4/popper.min.js",
        "vendor/bootstrap4/bootstrap.min.js",
        // "vendor/counter-up/jquery.waypoints.min.js",
        // "vendor/counter-up/jquery.counterup.min.js",
        "vendor/lightbox2/src/js/lightbox.js",

        "vendor/owl-carousel/owl.carousel.min.js",
        "vendor/revolution/jquery.themepunch.revolution.min.js",
        "vendor/revolution/jquery.themepunch.tools.min.js",

        // "vendor/revolution/local/revolution.extension.migration.min.js",
        // "vendor/revolution/local/revolution.extension.actions.min.js",
        // "vendor/revolution/local/revolution.extension.carousel.min.js",
        // "vendor/revolution/local/revolution.extension.kenburn.min.js",
        
        "vendor/revolution/local/revolution.extension.layeranimation.min.js",
        "vendor/revolution/local/revolution.extension.navigation.min.js",
        
        // "vendor/revolution/local/revolution.extension.parallax.min.js",
        
        "vendor/revolution/local/revolution.extension.slideanims.min.js",
        
        // "vendor/revolution/local/revolution.extension.video.min.js",
        "js/main.js",
        "js/revo-custom.js",
        "js/wow-custom.js",
        // "js/count.js",
        // 'js/image-map.js',
        '../js/select2/select2.min.js',
        "charts/apexcharts.js",
    ];

    public $depends = [
    ];
}
