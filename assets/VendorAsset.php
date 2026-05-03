<?php

namespace app\assets;
use yii\web\AssetBundle;

class VendorAsset extends AssetBundle
{
  public $basePath = '@webroot';
  public $baseUrl = '@web';

  public $css = [
    'css/autoComplete.css',
  ];
  public $js = [
    'js/autoComplete.min.js',
  ];
}