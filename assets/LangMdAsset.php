<?php

namespace nikitakls\markdown\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

class LangMdAsset extends AssetBundle
{
    public $sourcePath = '@vendor/nikitakls/yii2-editor-md/assets/lang';

    /**
     * @inheritdoc
     */
    public $js = [];

    public function registerLanguage($lang){
        $this->js[] = $lang.'.js';
    }

}