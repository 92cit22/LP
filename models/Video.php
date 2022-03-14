<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "Video".
 *
 * @property int $Id
 * @property string $Title
 * @property string $Description
 * @property string $Url
 * @property DateTime $CreatedAt
 * @property int $UserId
 * @property int $CategoryId
 *
 * @property Categories $category
 * @property Commentaries[] $commentaries
 * @property Likes[] $likes
 * @property User $user
 */
class Video extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Videos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Title', 'Description', 'UserId', 'CategoryId'], 'required'],
            [['Title', 'Description'], 'string'],
            // [['CreatedAt'], 'datetime', 'format' => 'php:Y-m-d', 'message' => 'Неверный формат'],
            [['UserId', 'CategoryId', 'CreatedAt'], 'default', 'value' => null],
            [['UserId', 'CategoryId'], 'integer'],
            [['CategoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['CategoryId' => 'Id']],
            [['UserId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['UserId' => 'Id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Title' => 'Title',
            'Description' => 'Description',
            'CreatedAt' => 'Created At',
            'UserId' => 'User ID',
            'CategoryId' => 'Category ID',
        ];
    }

    // public function setCreatedAt(string $value)
    // {
    //     DateTime($value)
    // }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['Id' => 'CategoryId']);
    }

    /**
     * Gets query for [[Commentaries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommentaries()
    {
        return $this->hasMany(Commentaries::className(), ['VideoId' => 'Id']);
    }

    /**
     * Gets query for [[Likes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Likes::className(), ['VideoId' => 'Id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['Id' => 'UserId']);
    }
}
