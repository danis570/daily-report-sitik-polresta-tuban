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

        $data = array_merge(self::$globalData, $model);
        extract($data);

        require_once __DIR__ . '/../View/' . $layouts . '/header.php';
        require_once __DIR__ . '/../View/' . $view . '.php';
        require_once __DIR__ . '/../View/' . $layouts . '/footer.php';

        if (!defined('PHPUNIT_COMPOSER_INSTALL') && !defined('__PHPUNIT_PHAR__')) {
            exit();
        }
    }

    public static function redirect(string $path)
    {
        header("Location: $path");
        exit();
    }

    public static function flashMessage(string $message, string $type = 'success'): void
    {

        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }

    public static function clearFlashMessage(): void
    {

        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }
}