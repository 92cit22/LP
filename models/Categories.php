<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Categories".
 *
 * @property int $ID
 * @property string $Title
 *
 * @property Video[] $videos
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Title'], 'required'],
            [['Title'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Title' => 'Title',
        ];
    }

    /**
     * Gets query for [[Videos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['CategoryId' => 'ID']);
    }
}
