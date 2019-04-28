<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

/** @var \yii\web\View $this */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;


$js = <<<JS

    $('.js_ok').click(function(){
        
        var btn = $(this);
        
        var id = $(this).attr('data_id');
        
            $.ajax({
              url: "/admin/allow",
              method: "POST",
              data: { id: id}
            }).done(function() {
                btn.removeClass('no_check');
                $('.js_remove[data_id='+id+']').addClass('no_check');
            });
    });

    $('.js_remove').click(function(){
        
        var btn = $(this);
        
        var id = $(this).attr('data_id');
        
            $.ajax({
              url: "/admin/remove",
              method: "POST",
              data: { id: id}
            }).done(function() {
                btn.removeClass('no_check');
                $('.js_ok[data_id='+id+']').addClass('no_check');
            });
    });

    $('.sel_img').click(function(){
        var id = $(this).attr('img_id');
        
        var t = $(this);
        
        $.ajax({
              url: "/admin/active",
              method: "POST",
              data: { id: id}
        }).done(function() {
            t.parent().find('.sel_img').removeClass('active');
            t.addClass('active');
        });
        
    });
    
JS;

$this->registerJs($js);

?>
<style>
    .no_check {
        opacity: 0.3;
    }

    .no_check:hover {
        opacity: 1;
    }

    .sel_img {
        float: left;
        padding: 4px;
        cursor: pointer;
    }

    .sel_img.active {
        background-color: #9acfea;
    }

</style>
<p><?= Html::a('parsing instagram', ['site/download'], ['data-method' => 'post', 'class' => 'btn btn-success']) ?>
<div class="image-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id',
                'value' => function ($item) {
                    return $item->id;
                },
                'contentOptions' => ['style' => 'width:50px;']
            ],
            'username',
            [
                'label' => 'Post',
                'format' => 'raw',
                'value' => function ($item) {

                    if (($item->format == 'video' || substr($item->url, -3) == 'mp4') && $item->from != 'vk') {
                        return '
                        <video width="240px" height="240px" controls>
                             <source src="' . $item->url . '" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>';
                    }

                    return Html::img($item->url, ['style' => 'max-width: 240px; max-height: 240px']);
                }
            ], [
                'label' => 'gallery',
                'format' => 'raw',
                'value' => function ($item) {
                    $out = '';
                    if ($item->format == 'sidecar') {
                        foreach ($item->images as $img) {
                            $out .=

                                Html::tag('div',
                                    Html::img($img->url, ['style' => 'max-width:  1200px; max-height: 120px; padding: 5px;']),
                                    ['class' => 'sel_img ' . (($img->active) ? 'active' : ''), 'img_id' => $img->id]
                                );
                        }
                    }
                    return $out;
                },
                'contentOptions' => ['style' => 'width:500px;']
            ],
            [
                'format' => 'raw',
                'attribute' => 'link',
                'value' => function ($item) {
                    return Html::a('link', $item->link, ['target' => '_blank']);
                }

            ],
//            'from',
//            [
//                'filter'=> \common\models\Post::getTextTags(),
//                'attribute' => 'tag_id',
//                'format' => 'html',
//                'label' => 'tag',
//                'value' => function ($item) {
//                    return \common\models\Post::getTextTags()[$item->tag_id];
//                }
//            ],
            [
                'format' => 'raw',
                'value' => function ($item) {

                    return '<button data_id=' . $item->id . ' class="js_ok button glyphicon glyphicon-ok btn-success ' . (($item->status != 1) ? 'no_check' : '') . ' "></button>
                            <button data_id=' . $item->id . ' class="js_remove button glyphicon glyphicon-remove btn-danger  ' . (($item->status != 2) ? 'no_check' : '') . ' style="margin-left: 10px;"></button>';
                }
            ]

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>