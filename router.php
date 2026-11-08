<?php

/**
 * 本地开发用前端控制器路由脚本（等价于 php artisan serve 的路由转发）。
 * 用法：php -S 0.0.0.0:8080 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// 静态文件直接返回，其余请求统一交给 Laravel 前端控制器
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
