<?php
/**
 * MarkdonModelBehavior.php
 * User: nikitakls
 * Date: 31.01.18
 * Time: 16:04
 */

namespace nikitakls\markdown\behaviors;

use cebe\markdown\Markdown;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class MarkdownModelBehavior extends Behavior
{
    /**
     * @var string source attribute name
     */
    public $sourceAttribute = 'content';

    /**
     * @var string destination attribute name for result
     */
    public $destinationAttribute = 'clean_content';

    /**
     * @var bool
     */
    public $processOnBeforeSave = true;

    /**
     * @var bool save or don't save result to DB
     */
    public $updateOnAfterFind = true;

    /**
     * @var callable
     */
    public $parser;

    private $_contentHash = '';

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }


    /**
     * @return void
     */
    public function beforeSave()
    {
        if (!$this->processOnBeforeSave) {
            return;
        }
        $content = $this->owner->{$this->sourceAttribute};
        $hash = $this->calculateHash($content);
        if ($hash == $this->_contentHash && !empty($this->owner->{$this->destinationAttribute})) {
            return;
        }
        $this->owner->{$this->destinationAttribute} = $this->processContent($content);
    }

    /**
     * @param $content
     * @return string
     */
    private function calculateHash($content)
    {
        return md5($content);
    }

    /**
     * @param $content
     * @return string
     */
    protected function processContent($content)
    {
        $parser = $this->parser;
        if (is_null($parser)) {
            $parser = function () use ($content) {
                return (new Markdown())->parse($content);
            };
        }
        $content = call_user_func($parser, $content);
        return $content;
    }

    /**
     * @return void
     */
    public function afterFind()
    {
        $content = $this->owner->{$this->sourceAttribute};
        $this->_contentHash = $this->calculateHash($content);
        if (empty($content) && $this->updateOnAfterFind) {
            $this->updateModel();
        }
    }

    protected function updateModel()
    {
        $model = $this->owner;
        $model->updateAttributes(array(
            $this->destinationAttribute => $this->processContent($model->{$this->sourceAttribute})
        ));
    }
}