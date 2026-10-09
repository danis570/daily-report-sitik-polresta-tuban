<?php

// ============================================================
// 1. Environment
// ============================================================

date_default_timezone_set('Asia/Jakarta');

// ============================================================
// 2. Session
// ============================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// 3. Autoload
// ============================================================
require_once __DIR__ . '/../vendor/autoload.php';

// ============================================================
// 4. Imports
// ============================================================
use Unirow2026\DailyReportSitikPolrestaTuban\App\Database;
use Unirow2026\DailyReportSitikPolrestaTuban\App\Router;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\AttendanceController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\AttendanceAdminController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\HomeController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\UserController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ProfileController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ReportController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ReportGenerateController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ReportItemController;
use Unirow2026\DailyReportSitikPolrestaTuban\Controller\ReportOptionController;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustAdminMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustNotLoginMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustLoginMiddleware;
use Unirow2026\DailyReportSitikPolrestaTuban\Middleware\MustUserMiddleware;

// ============================================================
// 5. Bootstrap
// ============================================================
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

// REPORT GENERATE CONTROLLER
$router->get('/report/generate', [ReportGenerateController::class, 'form'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/report/generate', [ReportGenerateController::class, 'postGenerate'], [MustLoginMiddleware::class, MustUserMiddleware::class]);

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

// ATTENDANCE CONTROLLER
$router->get('/absen', [AttendanceController::class, 'scan'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/absen/scan', [AttendanceController::class, 'postScan'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/absen/manual', [AttendanceController::class, 'manual'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->post('/absen/manual', [AttendanceController::class, 'postManual'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/absen/rekap', [AttendanceController::class, 'rekapHarian'], [MustLoginMiddleware::class, MustUserMiddleware::class]);
$router->get('/absen/rekap/bulanan', [AttendanceController::class, 'rekapBulanan'], [MustLoginMiddleware::class, MustUserMiddleware::class]);

// ADMIN ATTENDANCE CONTROLLER
$router->get('/admin/attendance', [AttendanceAdminController::class, 'index'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/edit/{id}', [AttendanceAdminController::class, 'edit'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/admin/attendance/edit/{id}', [AttendanceAdminController::class, 'postEdit'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/admin/attendance/delete/{id}', [AttendanceAdminController::class, 'delete'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/rekap', [AttendanceAdminController::class, 'rekapHarian'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/rekap-bulanan', [AttendanceAdminController::class, 'rekapBulanan'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/qr', [AttendanceAdminController::class, 'qr'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/status', [AttendanceAdminController::class, 'status'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/rekap/pdf', [AttendanceAdminController::class, 'pdfHarian'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/rekap-bulanan/pdf', [AttendanceAdminController::class, 'pdfBulanan'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/rekap-bulanan/pdf-harian', [AttendanceAdminController::class, 'pdfBulananHarian'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);

// ADMIN ATTENDANCE — STATUS CRUD
$router->get('/admin/attendance/status/add', [AttendanceAdminController::class, 'addStatus'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/admin/attendance/status/add', [AttendanceAdminController::class, 'postAddStatus'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->get('/admin/attendance/status/edit/{code}', [AttendanceAdminController::class, 'editStatus'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/admin/attendance/status/edit/{code}', [AttendanceAdminController::class, 'postEditStatus'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);
$router->post('/admin/attendance/status/delete/{code}', [AttendanceAdminController::class, 'deleteStatus'], [MustLoginMiddleware::class, MustAdminMiddleware::class]);

$router->run();