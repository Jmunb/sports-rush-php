<?php

namespace import;

use common\models\Image;
use common\models\Post;
use InstagramScraper\Exception\InstagramException;
use InstagramScraper\Instagram as Inst;
use Yii;

class Instagram
{

    public function load()
    {
        try {
            $instagram = Inst::withCredentials('rybartem@yandex.ru', '1qaz2wsx');
        } catch (\Exception $e) {
            echo 'Inst not init';
            var_dump($e);
            return 0;
        }

        $tags = Post::getTextTags();

        foreach ($tags as $key => $tag) {

            echo $key . " " . $tag . "\n";

//            if ($key != 5) continue;

            try {
                $result = $instagram->getMediasByTag($tag, 20);
            } catch (InstagramException $e) {
                echo $tag . "\n";
                exit;
                var_dump($e->getMessage());
                continue;
            }
            foreach ($result as $item) {

//                if ($item->getType() != 'sidecar') {
//                    continue;
//                }

                /** @var $item \InstagramScraper\Model\Media */
                if (in_array($item->getType(), ['image', 'sidecar', 'video'])) {


                    $count = Post::find()->where(['external_id' => $item->getId(), 'from' => 'instagram'])->count();
                    if ($count == 0) {

                        $post = new Post();
                        $post->external_id = $item->getId();

                        if ($item->getType() == 'video') {
                            $post->url = $item->getImageHighResolutionUrl();

                            if (!$post->url) {
                                $post->url = $item->getImageThumbnailUrl();
                            }

                            if (!$post->url) {
                                $post->url = $item->getImageLowResolutionUrl();
                            }
                        } else  $post->url = $item->getImageHighResolutionUrl();

                        $text = $item->getCaption();

                        $post->width = 0;
                        $post->height = 0;
                        $post->created_time = $item->getCreatedTime();
                        $post->link = 'https://www.instagram.com/p/' . $item->getShortCode();

                        $post->user_id = $item->getOwnerId();//$item->user->id;
                        $post->full_name = $item->getOwner()->getFullName();
                        $post->username = $item->getOwner()->getUsername();


                        if ($post->username == '') {
                            $acc = $instagram->getAccountById($post->user_id);

                            $post->full_name = $acc->getFullName();
                            $post->username = $acc->getUsername();
                        }

                        $post->profile_picture = $acc->getProfilePicUrl();
                        $post->status = 0;

                        $post->format = $item->getType();
                        $post->from = 'instagram';
                        $post->tag_id = $key;

                        $post->text = $item->getCaption();
//                        $image->count_slice = 0;

                        $post->created_at = time();
                        if ($post->save()) {
                            $im = new Image();
                            $im->post_id = $post->id;

                            $im->width = $post->width;
                            $im->height = $post->height;
                            $im->url = $post->url;
                            $im->created_time = $post->created_time;
                            $im->link = $post->link;
                            $im->format = $post->format;
                            $im->count_slice = 0;
                            $im->active = 1;
                            $im->from = $post->from;
                            $im->created_at = time();
                            $im->save();
                        };
                    } else {
                        echo("уже есть в базе\n");
                    }


                    if ($item->getType() == 'sidecar') {

                        $media = $instagram->getMediaByUrl('https://www.instagram.com/p/' . $item->getShortCode());

                        $post = Post::find()->where(['external_id' => $item->getId(), 'from' => 'instagram', 'format' => 'sidecar'])->one();


                        foreach ($media->getSidecarMedias() as $k => $sidecarMedia) {

                            if ($k == 0) continue;

                            if ($sidecarMedia->getType() == 'video') {
//                                $post->url = $sidecarMedia->getVideoStandardResolutionUrl();
                                $post->url = $sidecarMedia->getImageHighResolutionUrl();

                                if (!$post->url) {
                                    $post->url = $sidecarMedia->getImageThumbnailUrl();
                                }

                                if (!$post->url) {
                                    $post->url = $sidecarMedia->getImageLowResolutionUrl();
                                }

                            } else {
                                $post->url = $sidecarMedia->getImageHighResolutionUrl();
                            }

                            $cnt = Image::find()->where(['post_id' => $post->id, 'count_slice' => $k])->count();
                            if ($cnt == 0) {
                                $im = new Image();
                                $im->post_id = $post->id;

                                $im->width = $post->width;
                                $im->active = 0;
                                $im->height = $post->height;
                                $im->url = $post->url;
                                $im->created_time = $post->created_time;
                                $im->link = $post->link;
                                $im->format = $sidecarMedia->getType();
                                $im->count_slice = $k;
                                $im->from = $post->from;
                                $im->created_at = time();
                                $im->save();
                            }
                        }
                    }
                }
            }
        }
    }


}