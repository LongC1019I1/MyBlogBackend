<?php

namespace console\controllers;


use common\models\Admin;
use common\models\Role;
use common\models\Team;
use common\models\User;
use common\models\UserRole;
use Exception;
use PHPUnit\Util\Json;
use Yii;
use yii\console\Controller;
use yii\helpers\VarDumper;
use yii\rbac\Role;

class SeedDataController extends Controller
{

    public function actionData()
    {


        $roleData = [
            [
                'name'          => 'Admin',
                'permission'   => ['admin' => ['index', 'view', 'create', 'update', 'delete', 'reset-password'], 'site' => ['index', 'login', 'logout', 'district'], 'user-role' => ['index', 'view', 'create', 'update', 'delete']]

            ],
            [
                'name'          => 'Sale',
                'permission'   => ['admin' => ['index', 'view', 'create', 'update', 'delete', 'reset-password'], 'site' => ['index', 'login', 'logout', 'district'], 'user-role' => ['index', 'view', 'create', 'update', 'delete']]
            ]
        ];

        $role_admin = null;
        foreach ($roleData as $role) {
            $oldRole = Roles::findOne(['name' => $role['name']]);
            if ($oldRole) {
                $oldRole->permission = json_encode($role['permission']);
                $oldRole->save();
                if ($oldRole->name == 'Admin') {
                    $role_admin = $oldRole->id;
                }
                continue;
            }
            $roleModel = new Roles();
            $roleModel->name = $role['name'];
            $roleModel->permission = json_encode($role['permission']);
            if (!$roleModel->save()) {
                Yii::error($roleModel->getErrors());
                VarDumper::dump($roleModel->getErrors());
                throw new Exception('Seed data error');
            }
            if ($roleModel->name == 'Admin') {
                $role_admin = $roleModel->id;
            }
        }

        $oldUser = User::findOne(['username' => 'admin']);
        if ($oldUser) {
            return;
        }
        $userModel = new User();

        $userModel->username = 'admin';
        $userModel->email = 'admin@gmail.com';
        $userModel->auth_key = '1234567890';
        $userModel->setPassword('12345678');

        if ($userModel->save()) {
            echo 'User created!\n';
            return 0;
        } else {
            Yii::error($userModel->getErrors());
            VarDumper::dump($userModel->getErrors());
            throw new Exception('Cannot create User!');
        }
    }

}
