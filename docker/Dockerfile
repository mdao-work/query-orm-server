FROM registry.cn-hangzhou.aliyuncs.com/mdao/docker_php74:latest

WORKDIR /www

COPY . /www
COPY ./.env.development /www/.env

COPY docker/crontab/root /etc/crontabs/root
COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
RUN chmod -R 777 storage/
RUN composer install --no-dev --optimize-autoloader  && composer clearcache


