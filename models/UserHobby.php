<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_hobby".
 *
 * @property int $id
 * @property int $user_id
 * @property int $hobby_id
 */
class UserHobby extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_hobby';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'hobby_id'], 'required'],
            [['user_id', 'hobby_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'hobby_id' => 'Hobby',
        ];
    }
}
