<?php
/**
 * Created by PhpStorm.
 * User: VisioN
 * Date: 27.04.2015
 * Time: 16:59
 */

namespace vision\ytbwidget;


class YouTubeAsset extends \yii\web\AssetBundle{
    public $sourcePath = '@vendor/vision/yii2-you-tube-widget';
    public $js = [
        'js/api_you_tube.js',
    ];
}


