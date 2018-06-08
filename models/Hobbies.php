<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "hobbies".
 *
 * @property int $id
 * @property string $hobby
 */
class Hobbies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hobbies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hobby'], 'required'],
            [['hobby'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hobby' => 'Hobby',
        ];
    }
}
