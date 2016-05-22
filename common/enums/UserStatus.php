<?php
/**
 * Created by PhpStorm.
 * User: kkurkin
 * Date: 4/24/15
 * Time: 2:04 PM
 */

namespace common\enums;


class UserStatus extends Enum
{
    const DELETED = 0;
    const ACTIVE = 10;

    public static function getList()
    {
        return [
            self::DELETED => "Удален",
            self::ACTIVE => "Активен",
        ];
    }
}