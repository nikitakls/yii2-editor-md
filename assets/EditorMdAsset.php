<?php

namespace nikitakls\markdown\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

class EditorMdAsset extends AssetBundle
{
    public $sourcePath = '@bower/vin.editor.md';

    public function init()
    {
        $this->css = ['css/editormd.min.css', 'css/editormd.logo.min.css', 'css/editormd.preview.min.css'];
        $this->js = ['editormd.min.js'];
    }

}