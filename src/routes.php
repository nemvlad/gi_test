<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\controllers\UserController;

// Routes
$app->group('api', function(){
    $this->get('/user', UserController::class);
    //$this->post('/user', IssueController::class);
});

$app->get('/', function (Request $request, Response $response, array $args) use ($app) {
});


$app->map(['GET', 'POST'], '/user', UserController::class);
