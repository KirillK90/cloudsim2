<?php

return [
    'admin' => [
        'username' => 'admin',
        'email' => 'admin@polygant.ru',
        'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
        'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('123456'),
    ],
];