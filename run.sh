#!/bin/sh

# 启动前自动执行数据库迁移（建表），失败不阻塞服务启动
php artisan migrate --force || echo "[warn] database migrate failed, service will start anyway"

# 后台启动
php-fpm -D
# 关闭后台启动，hold住进程
nginx -g 'daemon off;'
