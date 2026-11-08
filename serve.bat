@echo off
chcp 65001 >nul
cd /d %~dp0
echo ============================================
echo  Laravel 本地开发服务器
echo  地址: http://127.0.0.1:8080
echo  按 Ctrl+C 停止
echo ============================================
php -S 0.0.0.0:8080 router.php
