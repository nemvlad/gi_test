<?php

namespace App\models;

use App\config\db\User;

class UserModel extends BaseModel {

    function getTableName()
    {
        return User::getTableName();
    }

} 