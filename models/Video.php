<?php

namespace app\models;

use DateTime;
use Yii;
use yii\web\UploadedFile;

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
    public $video;
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
            [['UserId', 'CategoryId'], 'default', 'value' => null],
            [['UserId', 'CategoryId'], 'integer'],
            ['video', 'file', 'extensions' => 'mp4,3gp,mov,m4v,mpeg,mpg'],
            ['CategoryId', 'exist', 'skipOnError' => true, 'targetClass' => Categories::className(), 'targetAttribute' => ['CategoryId' => 'Id']],
            ['UserId', 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['UserId' => 'Id']],
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

    public function upload()
    {
        $this->video = UploadedFile::getInstance($this, 'video');
        if (!$this->validate())
            return false;
        if (!empty($this->video)) {
            $temp_name = time() . '.' . $this->video->extension;
            if ($this->video->saveAs(Upload . $temp_name)) {
                $this->Url = Upload . $temp_name;
                $this->video = null;
            }
        }
        return true;
    }

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
