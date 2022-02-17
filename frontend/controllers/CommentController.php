<?php

namespace frontend\controllers;

use common\models\Comment;


class CommentController extends ApiController
{
    public $modelClass = Comment::class;
}
