nikitakls/yii2-editor-md
=============================
editor.md for Yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require nikitakls/yii2-editor-md
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

Markdown behavior
--------------
This behavior save html render markdown in active record model attribute.
You can use markdown behavior for ActiveRecord Model next:
```
use nikitakls\markdown\behaviors\MarkdownModelBehavior

class Content extents ActiveRecord{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => MarkdownModelBehavior::className(),
                'sourceAttribute' => 'content',
                'destinationAttribute' => 'clean_content',
            ],
        ];
    }

}
```

Markdown action for upload files
--------------
Configure you controller if you want to have upload image functionality:
```
use nikitakls\markdown\actions\UploadFileAction; 

class ContentController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {

        return [
            'upload-image' => [
                'class' => UploadFileAction::class,
                'url' => '@fileUrl/origin/puzzle/',
                'path' => '@filePath/origin/puzzle/',
                'thumbPath' => '@filePath/thumb/puzzle/',
                'thumbUrl' => '@fileUrl/thumb/puzzle/',
                'thumbs' => [
                    'puzzle' => [
                        'width' => 480,
                        'height' => 320,
                        'main' => true
                    ],
                ],
                'unique' => true,
                'validatorOptions' => [
                    'maxWidth' => 1600,
                    'maxHeight' => 1200
                ]
            ],
        ];
    }

```