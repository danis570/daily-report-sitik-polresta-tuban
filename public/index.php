<?php

use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Router;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\HomeController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\UserController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ProfileController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ReportController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ReportItemController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ReportOptionController;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustAdminMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustNotLoginMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustLoginMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustUserMiddleware;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

Database::getConnection('prod');
$router = new Router();

// HOME CONTROLLER
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about'], [MustNotLoginMiddleware::class]);

// USER CONTROLLER
$router->get('/register', [UserController::class, 'register'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/login', [UserController::class, 'login'], [MustNotLoginMiddleware::class]);
$router->get('/logout', [UserController::class, 'logout'], [MustLoginMiddleware::class]);
$router->get('/users', [UserController::class, 'users'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/register', [UserController::class, 'postRegister'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/login', [UserController::class, 'postLogin'], [MustNotLoginMiddleware::class]);
$router->post('/user/delete', [UserController::class, 'postDelete'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);

// PROFILE CONTROLLER
$router->get('/profile', [ProfileController::class, 'profile'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/profile', [ProfileController::class, 'postUpdate'], [MustLoginMiddleware::class, MustUserMiddleware::class]);

// REPORT OPTION CONTROLLER
$router->get('/report/options', [ReportOptionController::class, 'reportOptions'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/option/add', [ReportOptionController::class, 'addOption'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/option/edit/{id}', [ReportOptionController::class, 'editOption'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/option/quick-add', [ReportOptionController::class, 'quickAdd'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/option/add', [ReportOptionController::class, 'postAddOption'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/option/edit/{id}', [ReportOptionController::class, 'postEditOption'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/option/delete/{id}', [ReportOptionController::class, 'postDeleteOption'], [MustLoginMiddleware::class, MustUserMiddleware::class]);

// REPORT ITEM CONTROLLER
$router->get('/report/item/{date}/add', [ReportItemController::class, 'reportItemAdd'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/item/edit/{id}', [ReportItemController::class, 'editItem'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/item/edit/{id}', [ReportItemController::class, 'postEditItem'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/item/{date}/add', [ReportItemController::class, 'postReportItemAdd'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/item/delete/{id}', [ReportItemController::class, 'postDeleteItem'], [MustLoginMiddleware::class, MustUserMiddleware::class]);

// REPORT CONTROLLER
$router->get('/reports', [ReportController::class, 'reports'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/add', [ReportController::class, 'add'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/edit/{id}', [ReportController::class, 'edit'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/print/pdf/{startDate}/{endDate}', [ReportController::class, 'pdfRange'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/print/pdf/{date}', [ReportController::class, 'pdf'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/tracking', [ReportController::class, 'tracking'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/report/{date}', [ReportController::class, 'detail'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/duplicate/{id}', [ReportController::class, 'postDuplicate', [MustLoginMiddleware::class, MustUserMiddleware::class]]);
$router->post('/report/tracking', [ReportController::class, 'postTracking'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/edit/{id}', [ReportController::class, 'postEdit'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/delete/{id}', [ReportController::class, 'postDelete'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/add', [ReportController::class, 'postAdd'], [MustLoginMiddleware::class, MustUserMiddleware::class]);

$router->run();