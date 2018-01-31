<?php

namespace nikitakls\markdown;

use nikitakls\markdown\assets\EditorMdAsset;
use nikitakls\markdown\assets\LangMdAsset;
use yii\bootstrap\InputWidget;
use yii\helpers\Html;
use yii\helpers\Json;

class EditorMdWidget extends InputWidget
{
    /**
     * editor options
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var string $language
     */
    public $language;

    /**
     * Renders the widget.
     */
    public function run()
    {
        if ($this->hasModel()) {
            $this->name = empty($this->options['name']) ? Html::getInputName($this->model, $this->attribute) :
                $this->options['name'];
            $this->value = Html::getAttributeValue($this->model, $this->attribute);
        }
        echo Html::tag('div', '', $this->options);
        $this->registerClientScript();
    }

    protected function registerClientScript()
    {
        $view = $this->getView();
        $this->initClientOptions();
        $editor = EditorMdAsset::register($view);
        if (!is_null($this->language)) {
            $langAsset = LangMdAsset::register($view);
            $langAsset->registerLanguage($this->language);
        }
        $this->clientOptions['path'] = $editor->baseUrl . '/lib/';
        $jsOptions = Json::encode($this->clientOptions);
        $id = $this->options['id'];

        if ($this->clientOptions['emoji']) {
            $emoji = 'editormd.emoji = ' . Json::encode(['path' => 'http://www.webpagefx.com/tools/emoji-cheat-sheet/graphics/emojis/', 'ext' => ".png"]);
            $view->registerJs($emoji);
        }
        $jsOptions = 'var mdOptions = ' . $jsOptions . ';';
        $view->registerJs($jsOptions);

        $js = 'var openEditor' . crc32($id) . ' = editormd("' . $id . '", jQuery.extend(mdOptions, ' . Json::encode([
                'markdown' => $this->value ? $this->value : '',
                'name' => $this->name,
            ]) . '));';

        $view->registerJs($js);

    }

    public function initClientOptions()
    {

        $options = [
            'watch' => true,
            'emoji' => true,
            'syncScrolling' => true,
            'searchReplace' => true,
            'taskList' => true,
            'tocm' => true,
            'tex' => true,
            'flowChart' => true,
            'sequenceDiagram' => true,
            'height' => "600",
            'htmlDecode' => "style,script,iframe|on*",
            'placeholder' => "欢迎使用MarkDown编辑器",
            'toolbarIcons' => [
                "undo", "redo", "|",
                "h1", "h2", "h3", "h4", "h5", "h6", "|",
                "bold", "del", "italic", "quote", "list-ul", "list-ol", "hr", "pagebreak", "|",
                "code", "preformatted-text", "code-block", "|",
                "image", "table", "link", "reference-link", "|",
                "datetime", "emoji", "html-entities", "|",
                "search", "goto-line", "ucwords", "uppercase", "lowercase", "clear", "|",
                "preview", "watch", "fullscreen", "|",
                "help"
            ],
        ];

        $this->clientOptions = array_merge($options, $this->clientOptions);
    }
}