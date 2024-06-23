<?php

use app\controllers\AuthController;
use app\controllers\PhotoController;
use app\controllers\SiteController;
use app\core\Application;
use app\models\User;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(dirname(__DIR__), $config);
$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->get('/upload',[PhotoController::class,'upload']);
$app->router->post('/upload',[PhotoController::class,'upload']);
$app->router->get('/my-photos',[PhotoController::class,'myPhotos']);
$app->router->post('/photos/delete',[PhotoController::class,'delete']);
$app->router->get('/photo-details',[PhotoController::class,'photoDetail']);
$app->router->get('/wishlist',[PhotoController::class,'wishlist']);

$app->run();
