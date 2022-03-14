<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Likes".
 *
 * @property int $VideoId
 * @property int $UserId
 * @property bool $Like
 *
 * @property User $user
 * @property Video $video
 */
class Likes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Likes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['VideoId', 'UserId', 'Like'], 'required'],
            [['VideoId', 'UserId'], 'default', 'value' => null],
            [['VideoId', 'UserId'], 'integer'],
            [['Like'], 'boolean'],
            [['UserId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['UserId' => 'Id']],
            [['VideoId'], 'exist', 'skipOnError' => true, 'targetClass' => Video::className(), 'targetAttribute' => ['VideoId' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'VideoId' => 'Video ID',
            'UserId' => 'User ID',
            'Like' => 'Like',
        ];
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

    /**
     * Gets query for [[Video]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(Video::className(), ['ID' => 'VideoId']);
    }
}
