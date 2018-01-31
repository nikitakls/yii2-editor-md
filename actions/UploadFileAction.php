<?php
namespace nikitakls\markdown\actions;
/**
 * UploadFileAction.php
 * User: nikitakls
 * Date: 17.01.18
 * Time: 16:40
 */

use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use PHPThumb\GD;

    /**
     * UploadFileAction for images and files.
     *
     * Usage:
     * use nikitakls\markdown\actions\UploadFileAction
     * ```php
     * public function actions()
     * {
     *     return [
     *         'upload-image' => [
     *             'class' => 'UploadFileAction::class',
     *             'url' => 'http://my-site.com/statics/',
     *             'path' => '/var/www/my-site.com/web/statics',
     *             'unique' => true,
     *             'validatorOptions' => [
     *                 'maxWidth' => 1000,
     *                 'maxHeight' => 1000
     *             ]
     *         ],
     *         'file-upload' => [
     *             'class' => 'UploadFileAction::class',
     *             'url' => 'http://my-site.com/statics/',
     *             'path' => '/var/www/my-site.com/web/statics',
     *             'uploadOnlyImage' => false,
     *             'translit' => true,
     *             'validatorOptions' => [
     *                 'maxSize' => 40000
     *             ]
     *         ]
     *     ];
     * }
     * ```
     *
     * @author Vasile Crudu <bazillio07@yandex.ru>
     *
     * @link https://github.com/vova07/yii2-imperavi-widget
     */
class UploadFileAction extends Action
{
    /**
     * @var string Path to directory where files will be uploaded.
     */
    public $path;

    /**
     * @var string thumb Path to directory where files will be uploaded.
     */
    public $thumbPath;

    /**
     * @var string URL path to directory where files will be uploaded.
     */
    public $url;

    /**
     * @var string URL path to directory where files will be uploaded.
     */
    public $thumbUrl;

    /**
     * @var string Validator name
     */
    public $uploadOnlyImage = true;

    /**
     * @var string Variable's name that Redactor sent upon image/file upload.
     */
    public $uploadParam = 'editormd-image-file';

    /**
     * @var bool Whether to replace the file with new one in case they have same name or not.
     */
    public $replace = false;

    /**
     * @var boolean If `true` unique filename will be generated automatically.
     */
    public $unique = true;

    /**
     * In case of `true` this option will be ignored if `$unique` will be also enabled.
     *
     * @var bool Whether to translit the uploaded file name or not.
     */
    public $translit = false;

    /**
     * @var array Model validator options.
     */
    public $validatorOptions = [];

    /**
     * @var string Model validator name.
     */
    private $_validator = 'image';

    /**
     * @var array thumbs profiles
     */
    public $thumbs = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->url === null) {
            throw new InvalidConfigException('The "url" attribute must be set.');
        } else {
            $this->url = rtrim($this->url, '/') . '/';
        }
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" attribute must be set.');
        } else {
            $this->path = rtrim(Yii::getAlias($this->path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            if (!FileHelper::createDirectory($this->path)) {
                throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }
        }
        if ($this->uploadOnlyImage !== true) {
            $this->_validator = 'file';
        }
        parent::init();
        \Yii::$app->controller->enableCsrfValidation = false;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        try{
            $result = $this->uploadImage();
        } catch (BadRequestHttpException $e){
            $result = [
                'success' => 0,
                'message' => $e->getMessage(),
            ];
        }

        return $result;
    }


    public function uploadImage(): array
    {
        if(!Yii::$app->request->isPost){
            throw new BadRequestHttpException('Only POST is allowed');
        }
        $model = $this->getImageModel();

        if (file_exists($this->path . $model->file->name) && $this->replace === false) {
            throw new BadRequestHttpException('File already exists');
        }
        $pathChank = self::getIdPath(md5($model->file->name));
        $filePath = $this->path . $pathChank;
        FileHelper::createDirectory(pathinfo($filePath, PATHINFO_DIRNAME), 0775, true);

        if ($model->file->saveAs($filePath. $model->file->name)) {
            $result = [
                'success' => 1,
                'message' => 'success',
                'id' => $model->file->name,
                'url' => $this->getUploadedFileUrl($this->url .$pathChank. $model->file->name)
            ];

            if ($this->uploadOnlyImage !== true) {
                $result['filename'] = $model->file->name;
            }
        } else {
            throw new BadRequestHttpException('Cannot upload file');
        }
        $thumbMainUrl = $this->createThumbs($pathChank, $model->file->name);
        if(!is_null($thumbMainUrl)){
            $result['url'] = $thumbMainUrl;;
        }

        return $result;
    }

    protected function getImageModel(): DynamicModel
    {
        $file = UploadedFile::getInstanceByName($this->uploadParam);
        $model = new DynamicModel(['file' => $file]);
        $model->addRule('file', $this->_validator, $this->validatorOptions)->validate();
        if ($model->hasErrors()) {
            throw new BadRequestHttpException($model->getFirstError('file'));
        }

        if ($this->unique === true && $model->file->extension) {
            $model->file->name = uniqid() . '.' . $model->file->extension;
        } elseif ($this->translit === true && $model->file->extension) {
            $model->file->name = Inflector::slug($model->file->baseName) . '.' . $model->file->extension;
        }

        return $model;
    }

    /**
     * Returns file url for the attribute.
     *
     * @param string $attribute
     * @return string|null
     */
    public function getUploadedFileUrl($url)
    {

        return Yii::getAlias($url);
    }


    /**
     * @param integer $id
     * @return string
     */
    protected static function getIdPath($id)
    {
        $id = is_array($id) ? implode('', $id) : $id;
        $length = 5;
        $id = str_pad($id, $length, '0', STR_PAD_RIGHT);

        $result = [];
        for ($i = 0; $i < $length; $i++) {
            $result[] = substr($id, $i, 1);
        }

        return implode('/', $result);
    }

    /**
     * Creates image thumbnails
     */
    public function createThumbs($pathChank, $fileName)
    {
        $path = $this->path.$pathChank.$fileName;
        $resultUrl = null;
        foreach ($this->thumbs as $profile => $config) {
            $thumbPath = static::getThumbFilePath($path, $profile);
            if (is_file($path) && !is_file($thumbPath)) {

                $thumb = new GD($path, $config);
                call_user_func(function (GD $thumb) use ($config) {
                    $thumb->adaptiveResize($config['width'], $config['height']);
                }, $thumb);
                FileHelper::createDirectory(pathinfo($thumbPath, PATHINFO_DIRNAME), 0775, true);
                $thumb->save($thumbPath);
            }
            if(isset($config['main']) && $config['main'] == true){
                $resultUrl = $this->getUploadedFileUrl($this->thumbUrl.$profile.DIRECTORY_SEPARATOR.$pathChank.$fileName);
            }
        }
        return $resultUrl;
    }

    public function getThumbFilePath($path, $profile)
    {

        return str_ireplace(Yii::getAlias($this->path), Yii::getAlias($this->thumbPath).$profile.DIRECTORY_SEPARATOR, $path);
    }
    
}