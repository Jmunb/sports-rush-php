<?php

use yii\db\Migration;

/**
 * Class m190427_105459_insta_init
 */
class m190427_105459_insta_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),

            'external_id' => $this->string(),

            'width' => $this->integer(),
            'height' => $this->integer(),
            'url' => $this->string(),

            'created_time' => $this->integer(),
            'link' => $this->string(),

            'user_id' => $this->bigInteger(),
            'full_name' => $this->string(),
            'username' => $this->string(),
            'profile_picture' => $this->string(),

            'format' => $this->string(),

            'tag_id' => $this->integer(),

            'status' => $this->integer(),

            'from' => $this->string(),

            'created_at' => $this->integer(),
        ]);

        $this->createIndex('ext_id', 'post', ['external_id'], true);




    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('images');
    }
}
