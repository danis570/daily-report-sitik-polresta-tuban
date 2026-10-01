<?php

use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Router;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\HomeController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\UserController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ProfileController;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustAdminMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustNotLoginMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustLoginMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustUserMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

Database::getConnection('prod');
$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about'], [MustNotLoginMiddleware::class]);
$router->get('/register', [UserController::class, 'register'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/login', [UserController::class, 'login'], [MustNotLoginMiddleware::class]);
$router->get('/logout', [UserController::class, 'logout'], [MustLoginMiddleware::class]);
$router->get('/users', [UserController::class, 'users'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/profile', [ProfileController::class, 'profile'], [MustLoginMiddleware::class, MustUserMiddleware::class]);

$router->post('/register', [UserController::class, 'postRegister'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/login', [UserController::class, 'postLogin'], [MustNotLoginMiddleware::class]);
$router->post('/user/delete', [UserController::class, 'postDelete'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/profile', [ProfileController::class, 'postUpdate'], [MustLoginMiddleware::class, MustUserMiddleware::class]);

$router->run();