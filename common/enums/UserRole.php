<?php

namespace common\enums;
/**
 * Created by PhpStorm.
 * User: omega
 * Date: 5/22/16
 * Time: 4:05 PM
 */
class UserRole extends Enum
{
    const ADMIN = 'admin';
    const USER = 'user';
    const GUEST = 'guest';

    static function getList()
    {
        return array(
            self::ADMIN => 'Админ',
            self::USER => 'Пользователь'
        );
    }
}