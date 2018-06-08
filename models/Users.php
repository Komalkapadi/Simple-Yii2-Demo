<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property int $gender 1=>male,2=>female
 * @property string $address
 * @property string $dob
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email','password', 'gender', 'address', 'dob'], 'required'],
            [['gender'], 'integer'],
            [['address'], 'string'],
            [['dob'], 'safe'],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 50],
            [['password'],'string', 'min'=>6],
            [['password'],'string', 'max'=>50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'gender' => 'Gender',
            'address' => 'Address',
            'dob' => 'Dob',
        ];
    }
}
