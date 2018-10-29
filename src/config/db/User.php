<?php

namespace App\config\db;

class User extends Generic
{
    const uuid = 'uuid';
    const uid = 'uid';
    const name = 'name';

    static function getTableName()
    {
        return 'users';
    }
}