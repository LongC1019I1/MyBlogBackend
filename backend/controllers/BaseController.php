<?php

namespace backend\controllers;

use common\models\Role;
use common\models\User;
use common\models\UserRole;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\Json;
use yii;

class BaseController extends Controller
{
    public $menuLeftItems = array();
    public function behaviors()
    {

        $user_id = Yii::$app->user->id;

        $is_access = false;

        $controller_id = Yii::$app->controller->id; //test
        $action_id = Yii::$app->controller->action->id; //index
        Yii::error($controller_id, $action_id);

        if (isset($user_id)) {

            //Get data
            $role_id = User::findOne($user_id)->role_id;
            Yii::info($role_id);
            $permission = Json::decode(Role::findOne($role_id)->permission);

            Yii::error($permission, '$permission');
            Yii::error($controller_id, '$controller_id');

            if (ArrayHelper::keyExists($controller_id, $permission)) {
                $is_access = in_array($action_id, $permission[$controller_id]);
            }

            $this->menuLeftItems = [
                [
                    'label' => Yii::t('app', 'Quản lý tài khoản'),
                    'visible' => isset($permission['admin']) && in_array('create', $permission['admin']) == true,
                    'icon' => 'user',
                    'url' => '#',
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Danh sách tài khoản'),
                            'icon' => 'users',
                            'url' => ['/admin'],
                            'visible' => isset($permission['admin']) && in_array('index', $permission['admin']) == true,
                        ],
                        [
                            'label' => Yii::t('app', 'Tạo tài khoản'),
                            'icon' => 'user-plus',
                            'url' => ['/admin/create'],
                            'visible' => isset($permission['admin']) && in_array('create', $permission['admin']) == true,
                        ],
                    ],
                    'active' => strpos(Yii::$app->request->url, 'admin') ? true : false,
                ],
                [
                    'label' => Yii::t('app', 'Phân quyền'),
                    'icon' => 'fa fa-key',
                    'url' => '/user-role/index',
                    'visible' => isset($permission['user-role']) && in_array('index', $permission['user-role']) == true,
                    'active' => strpos(Yii::$app->request->url, 'user-role') ? true : false,
                ],

            ];


            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => $is_access,
                            'denyCallback' => function ($rule, $action) {
                                Yii::$app->session->setFlash('error', '403: You are not allowed access this function');
                                if (Yii::$app->request->referrer) {
                                    return $this->redirect(Yii::$app->request->referrer);
                                } else {
                                    return $this->goHome();
                                }
                            },
                            'roles' => ['@'],
                        ]
                    ],
                ]
            ];
        } else {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['login', 'logout', 'error'],
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                        [
                            'allow' => $is_access,
                            'roles' => ['@'],
                        ],
                    ],
                ]
            ];
        }
    }
}
