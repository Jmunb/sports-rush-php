<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $post_id
 * @property int $width
 * @property int $height
 * @property string $url
 * @property int $created_time
 * @property string $link
 * @property string $format
 * @property int $count_slice
 * @property string $from
 * @property int $created_at
 *
 * @property Post $post
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'width', 'height', 'created_time', 'count_slice', 'created_at', 'active'], 'integer'],
            [['url', 'link', 'format', 'from'], 'string', 'max' => 255],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'width' => 'Width',
            'height' => 'Height',
            'url' => 'Url',
            'created_time' => 'Created Time',
            'link' => 'Link',
            'format' => 'Format',
            'count_slice' => 'Count Slice',
            'from' => 'From',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function fields()
    {
        return [
            'url',
            'active',
            'format'
        ];
    }
}
