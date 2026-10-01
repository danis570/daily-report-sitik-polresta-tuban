<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\App;

class View
{
    public static $globalData = [];

    public static function share(string $key, mixed $value): void
    {
        self::$globalData[$key] = $value;
    }

    public static function render(string $layouts, string $view, array $model)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = array_merge(self::$globalData, $model);
        extract($data);

        require_once __DIR__ . '/../View/' . $layouts . '/header.php';
        require_once __DIR__ . '/../View/' . $view . '.php';
        require_once __DIR__ . '/../View/' . $layouts . '/footer.php';
        exit();
    }

    public static function redirect(string $path)
    {
        header("Location: $path");
        exit();
    }

    public static function flashMessage(string $message)
    {
        session_start();
        $_SESSION['flash_message'] = $message;
    }

    public static function clearFlashMessage()
    {
        unset($_SESSION['flash_message']);
    }
}