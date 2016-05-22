<?php

namespace console\fixtures;

use common\enums\UserRole;
use common\models\User;
use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'common\models\User';

    public function load()
    {
        $this->resetTable();
        $username = 'admin';
        $password = '123456';
        $user = new User();
        $user->username = 'admin';
        $user->email = "$username@polygant.ru";
        $user->role = UserRole::ADMIN;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->save();
    }
}