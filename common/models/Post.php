<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $external_id
 * @property int $width
 * @property int $height
 * @property string $url
 * @property int $created_time
 * @property string $link
 * @property int $user_id
 * @property string $full_name
 * @property string $username
 * @property string $profile_picture
 * @property string $format
 * @property int $tag_id
 * @property int $status
 * @property string $from
 * @property int $created_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    public static function getTextTags()
    {
        return ['zenit'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['width', 'height', 'created_time', 'user_id', 'tag_id', 'status', 'created_at'], 'integer'],
            [['external_id', 'url', 'link', 'full_name', 'username', 'profile_picture', 'format', 'from'], 'string', 'max' => 255],
            [['external_id'], 'unique'],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'url',
            'full_name',
            'username',
            'format',
            'profile_picture',
            'format',
            'text',
            'images'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'external_id' => 'External ID',
            'width' => 'Width',
            'height' => 'Height',
            'url' => 'Url',
            'created_time' => 'Created Time',
            'link' => 'Link',
            'user_id' => 'User ID',
            'full_name' => 'Full Name',
            'username' => 'Username',
            'profile_picture' => 'Profile Picture',
            'format' => 'Format',
            'tag_id' => 'Tag ID',
            'status' => 'Status',
            'from' => 'From',
            'created_at' => 'Created At',
        ];
    }

    public function getImages()
    {
        return $this->hasMany(Image::className(), ['post_id' => 'id']);
    }
}
