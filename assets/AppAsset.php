<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@link';
    public $css = [
        // 'css/site.css',
        // 'css/masterslider.css',
        // 'css/iconpicker.css',
        // 'css/jquery.dataTables.min.css'
        'admin-assets/vendors/bower_components/morris.js/morris.css',
        'admin-assets/vendors/vectormap/jquery-jvectormap-2.0.2.css',
        'admin-assets/vendors/bower_components/datatables/media/css/jquery.dataTables.min.css',
        'admin-assets/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.css',
        'admin-assets/dist/css/style.css',
        // 'admin-assets/dist/css/font-awesome.min.css',
    ];
    public $js = [
        // 'js/jquery.js',
        // 'js/main.js',
        'js/masterslider.js',
        'js/jquery.easing.js',
        'js/rowsorter.js',
        'js/iconpicker.js',
        // 'js/jquery.dataTables.min.js'
        // 'admin-assets/vendors/bower_components/jquery/dist/jquery.min.js',
        'admin-assets/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js',
        'admin-assets/vendors/vectormap/jquery-jvectormap-2.0.2.min.js',
        'admin-assets/vendors/vectormap/jquery-jvectormap-world-mill-en.js',
        'admin-assets/dist/js/vectormap-data.js',
        'admin-assets/vendors/bower_components/moment/min/moment.min.js',
        'admin-assets/vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js',
        'admin-assets/dist/js/simpleweather-data.js',
        'admin-assets/vendors/bower_components/datatables/media/js/jquery.dataTables.min.js',
        'admin-assets/dist/js/jquery.slimscroll.js',

        'admin-assets/dist/js/dropdown-bootstrap-extended.js',

        // 'admin-assets/vendors/jquery.sparkline/dist/jquery.sparkline.min.js',

        'admin-assets/vendors/bower_components/raphael/raphael.min.js',
        'admin-assets/vendors/bower_components/morris.js/morris.min.js',
        // 'admin-assets/dist/js/morris-data.js',

        'admin-assets/vendors/chart.js/Chart.min.js',

        'admin-assets/vendors/bower_components/jquery-toast-plugin/dist/jquery.toast.min.js',

        'admin-assets/dist/js/init.js',
        // 'admin-assets/dist/js/dashboard2-data.js',

    ];
    public $depends = [
        // 'yii\web\YiiAsset',
        '\app\assets\AdminLtePluginAsset',
    //     'yii\bootstrap\BootstrapPluginAsset',
    //     'yii\bootstrap\BootstrapAsset',
    ];
}
