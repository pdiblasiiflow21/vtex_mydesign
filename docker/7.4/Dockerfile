FROM php:7.4-fpm

ARG PSR_VERSION=1.2.0

RUN set -xe && \
    # Download PSR, see https://github.com/jbboehr/php-psr
    curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && \
    tar xzf ${PWD}/v${PSR_VERSION}.tar.gz && \
    docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) \
        ${PWD}/php-psr-${PSR_VERSION} \
    && \
    # Remove temp files
    rm -r \
        ${PWD}/v${PSR_VERSION}.tar.gz \
        ${PWD}/php-psr-${PSR_VERSION} \
    && \
    php -m

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

RUN apt-get update && \
    apt-get install -y libzip-dev zip cron supervisor libicu-dev && \
    docker-php-ext-install zip intl pdo_mysql mbstring

RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug

RUN pecl install pcov && \
    echo "extension=pcov.so" > /usr/local/etc/php/conf.d/pcov.ini

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Add a sample cron job
COPY cronjob /etc/cron.d/mycron
RUN chmod 0644 /etc/cron.d/mycron && \
    crontab /etc/cron.d/mycron && \
    touch /var/log/cron.log

# Supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ENV WEB_DOCUMENT_ROOT=/var/www/html/public

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
