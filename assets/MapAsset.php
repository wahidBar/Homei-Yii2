<?php
namespace app\assets;


use yii\web\AssetBundle;

class MapAsset extends AssetBundle
{
    public $js = [
        // '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',
        "//maps.googleapis.com/maps/api/js?key=AIzaSyAjKSlPBmdJkSO1BY6Qt9gWBlmgVw6KXO4&libraries=places&region=id&language=en&sensor=false",
    ];
}