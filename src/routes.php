<?php

use App\controllers\UserController;
use App\controllers\GiftController;

// Routes
$app->group('/api', function(){
    $this->get('/user', UserController::class);
});

$app->map(['GET', 'POST', 'PUT', 'DELETE'], '/user[/{itemHandler}/]', UserController::class);
$app->map(['GET', 'POST', 'PUT', 'DELETE'], '/gift[/{itemHandler}/]', GiftController::class);
