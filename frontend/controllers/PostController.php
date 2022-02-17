<?php

namespace frontend\controllers;

use common\models\Post;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use Yii;
use yii\web\ForbiddenHttpException;

class PostController extends ApiController
{

    public $modelClass = Post::class;


}
