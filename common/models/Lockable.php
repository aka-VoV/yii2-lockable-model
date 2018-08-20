<?php
/**
 * Created by PhpStorm.
 * User: vov
 * Date: 8/16/18
 * Time: 3:00 PM
 */

namespace dkit\lockable\common\models;


use yii\db\ActiveRecord;

/**
 * Class Lockable
 * @package dkit\lockable\common\models
 *
 * @property integer $id
 * @property string $model_name
 * @property integer $model_id
 * @property integer $user_id
 * @property integer $unlock_at
 */
class Lockable extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%lockable}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_name', 'model_id', 'user_id', 'unlock_at'], 'required'],
            [['model_name'], 'string'],
            [['model_id', 'user_id', 'unlock_at'], 'integer'],
        ];
    }


}