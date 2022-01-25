<?php

namespace frontend\controllers;

use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class ApiController extends ActiveController

{
    const access_key = "da53fe41852c808128fe2c651543403d";

    public function behaviors()
    {

        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return $behaviors;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete']) && $model->created_by !== Yii::$app->user->id) {
            throw new ForbiddenHttpException("You do not have permission to change this record");
        }
    }
}
