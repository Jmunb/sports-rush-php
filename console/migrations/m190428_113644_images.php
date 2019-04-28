<?php

use yii\db\Migration;

/**
 * Class m190428_113644_images
 */
class m190428_113644_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('image', [
            'id' => $this->primaryKey(),

            'post_id' => $this->integer(),

            'width' => $this->integer(),
            'height' => $this->integer(),
            'url' => $this->string(),

            'created_time' => $this->integer(),
            'link' => $this->string(),

            'format' => $this->string(),

            'count_slice' => $this->integer(),

            'from' => $this->string(),

            'active' => $this->boolean(),

            'created_at' => $this->integer(),
        ]);

        $this->addForeignKey('post2image', 'image', 'post_id', 'post', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190428_113644_images cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190428_113644_images cannot be reverted.\n";

        return false;
    }
    */
}
