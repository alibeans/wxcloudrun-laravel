# 使用 Alpine 3.19（内置 PHP 8.2）+ Nginx
FROM alpine:3.19

# 容器默认时区为UTC，如需使用上海时间请启用以下时区设置命令
# RUN apk add tzdata && cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime && echo Asia/Shanghai > /etc/timezone

# 使用 HTTPS 协议访问容器云调用证书安装
RUN apk add ca-certificates

# 安装 PHP 8.2 及 Laravel 所需扩展 + Nginx
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.tencent.com/g' /etc/apk/repositories \
    && apk add --update --no-cache \
    php82 \
    php82-fpm \
    php82-ctype \
    php82-curl \
    php82-dom \
    php82-fileinfo \
    php82-mbstring \
    php82-openssl \
    php82-session \
    php82-tokenizer \
    php82-xml \
    php82-simplexml \
    php82-pdo \
    php82-pdo_mysql \
    php82-exif \
    nginx \
    && rm -f /var/cache/apk/*

# 设定工作目录
WORKDIR /app

# 将当前目录下所有文件拷贝到/app
COPY . /app

# 替换 nginx、fpm、php 配置
RUN cp /app/conf/nginx.conf /etc/nginx/conf.d/default.conf \
    && cp /app/conf/fpm.conf /etc/php82/php-fpm.d/www.conf \
    && cp /app/conf/php.ini /etc/php82/php.ini \
    && mkdir -p /run/nginx \
    && chmod -R 777 /app/storage \
    && ln -sf /usr/bin/php82 /usr/bin/php \
    && ln -sf /usr/sbin/php-fpm82 /usr/sbin/php-fpm

# 暴露端口
EXPOSE 80

# 容器启动执行脚本
CMD ["sh", "run.sh"]
