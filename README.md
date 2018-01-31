nikitakls/yii2-editor-md
=============================
editor.md for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist yii2-editor-md "*"
```

or add

```
"nikitakls/yii2-editor-md": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by:

```php
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use nikitakls\markdown\EditorMdWidget;

?>
<?php 
echo $form->field($model, 'info_md')->widget(EditorMdWidget::className(), [
                'options' => [// html attributes
                    'id' => 'editor-markdown',
                ],
                'language' => 'ru',
                'clientOptions' => [
                    'height' => '300',
                    // 'previewTheme' => 'dark',
                    // 'editorTheme' => 'pastel-on-dark',
                    'markdown' => '',
                    //'codeFold' => true,
                    'syncScrolling' => true,
                    'saveHTMLToTextarea' => true,
                    'searchReplace' => true,
                    'watch' => true, 
                    'htmlDecode' => 'style,script,iframe|on*',
                    //'toolbar' => false,             
                    'placeholder' => 'MarkDown',
                    'previewCodeHighlight' => false,  
                    'emoji' => true,
                    'taskList' => true,
                    'tocm' => true, 
                    'tex' => true,   
                    'flowChart' => true,            
                    'sequenceDiagram' => true,     
                    'imageUpload' => true,
                    'imageFormats' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp'],
                    'imageUploadURL' => Url::to(['file-upload', 'type' => 'md']),
                    'toolbarIcons' => [
                        "undo", "redo", "|",
                        "bold", "del", "italic", "list-ul", "list-ol", "hr", "|",
                        "code", "code-block", "|",
                        "image", "table", "link", "|",
                        "html-entities", "|",
                        "preview", "watch","|",
                        "help"
                    ],
                ]
            ]
) ?>

```
See more options [https://pandao.github.io/editor.md/en.html]