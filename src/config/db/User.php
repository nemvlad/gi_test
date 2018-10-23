<?php

namespace App\config\db;

use App\config\db\Generic;

class User extends Generic
{
    const uuid = 'uuid';
    const uid = 'uid';
    const name = 'name';

    static function getTableName()
    {
        return 'user';
    }
}