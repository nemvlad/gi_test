<?php

namespace App\controllers;

use App\models\UserModel;

class UserController extends BaseController {
    static function getModelName() {return UserModel::class;}
} 