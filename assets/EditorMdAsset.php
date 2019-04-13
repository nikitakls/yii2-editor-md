<?php

namespace nikitakls\markdown\assets;

use yii\web\AssetBundle;

class EditorMdAsset extends AssetBundle
{
    public $sourcePath = '@bower/editor.md';

    public function init()
    {
        $this->css = ['css/editormd.min.css', 'css/editormd.logo.min.css', 'css/editormd.preview.min.css'];
        $this->js = ['editormd.min.js'];
    }

}