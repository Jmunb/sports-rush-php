<?php

namespace console\controllers;

use import\Instagram;
use Yii;
use yii\console\Controller;

class ImportController extends Controller
{

    public function actionLoad()
    {
        $instagram = new Instagram();
        $instagram->load();
    }
}

