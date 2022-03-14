<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Commentaries".
 *
 * @property int $Id
 * @property string $Text
 * @property int $UserId
 * @property int $VideoId
 * @property string $CreatedAt
 *
 * @property User $user
 * @property Video $video
 */
class Commentaries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Commentaries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Text', 'UserId', 'VideoId', 'CreatedAt'], 'required'],
            [['Text'], 'string'],
            [['UserId', 'VideoId'], 'default', 'value' => null],
            [['UserId', 'VideoId'], 'integer'],
            [['CreatedAt'], 'safe'],
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
            'Id' => 'ID',
            'Text' => 'Text',
            'UserId' => 'User ID',
            'VideoId' => 'Video ID',
            'CreatedAt' => 'Created At',
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
