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
 * @property string $MimeType
 * @property DateTime $CreatedAt
 * @property int $UserId
 * @property int $CategoryId
 *
 * @property Categories $Category
 * @property Commentaries[] $commentaries
 * @property int $Likes
 * @property int $Dislikes
 * @property User $User
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
            [['Title', 'UserId', 'CategoryId', 'video'], 'required', 'message' => 'Поле "{attribute}" должно быть обязательно заполнено'],
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
            'Id' => 'Идентификатор',
            'Title' => 'Название',
            'Description' => 'Описание',
            'CreatedAt' => 'Дата создания',
            'UserId' => 'Пользователь',
            'CategoryId' => 'Категория',
            'video' => 'Видео',
            'Likes' => 'Понравилось',
            'Dislikes' => 'Непонравилось',
            'Url' => 'Видео',
        ];
    }

    public function upload()
    {
        $this->video = UploadedFile::getInstance($this, 'video');
        if (!$this->validate())
            return false;
        if (!empty($this->video)) {
            $temp_name = Upload . time() . '.' . $this->video->extension;
            $this->Url = $temp_name;
            $this->MimeType = $this->video->type;
            if (!$this->video->saveAs($temp_name))
                return false;
            $this->video->tempName = $this->Url;
        }
        return true;
    }

    public function getLikes()
    {
        return $this->allLikes()->where(['Like' => true])->count();
    }

    public function getDislikes()
    {
        return $this->allLikes()->where(['Like' => false])->count();
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
    public function allLikes()
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
