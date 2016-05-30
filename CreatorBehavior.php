<?php

namespace tugmaks\behaviors;

use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

/**
 * CreatorBehavior automatically fills the specified attributes with the current user id.
 *
 * To use CreatorBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use tugmaks\behaviors\CreatorBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         CreatorBehavior::className(),
 *     ];
 * }
 * ```
 *
 * By default, CreatorBehavior will fill the `created_by` and `updated_by` attributes with the current user id
 * when the associated AR object is being inserted; it will fill the `updated_by` attribute
 * with the current user id when the AR object is being updated. The user id value is obtained by `Yii::$app->user->id`.
 *
 * For the above implementation to work with MySQL database, please declare the columns(`created_by`, `updated_by`) as int(11).
 *
 * If your attribute names are different or you want to use a different way of retrieving user id,
 * you may configure the [[createdByAttribute]], [[updatedByAttribute]] and [[value]] properties like the following:
 *
 * ```php
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => CreatorBehavior::className(),
 *             'createdByAttribute' => 'creator_id',
 *             'updatedByAttribute' => 'updater_id',
 *             'value'=>function($event){
 *                  return \Yii::$app->user->identity->getCustomId();
 *              }
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Maxim Tugaev <tugmaks@yandex.ru>
 */
class CreatorBehavior extends AttributeBehavior
{
    /**
     * @var string the attribute that will receive timestamp value
     * Set this property to false if you do not want to record the creation time.
     */
    public $createdByAttribute = 'created_by';

    /**
     * @var string the attribute that will receive timestamp value.
     * Set this property to false if you do not want to record the update time.
     */
    public $updatedByAttribute = 'updated_by';

    /**
     * @inheritdoc
     *
     * In case, when the value is `null`, the result of the Yii::$app->user->id
     * will be used as value.
     */
    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdByAttribute, $this->updatedByAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedByAttribute,
            ];
        }
    }

    /**
     * @inheritdoc
     *
     * In case, when the [[value]] is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
     * will be used as value.
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return \Yii::$app->user->id;
        }
        return parent::getValue($event);
    }
}