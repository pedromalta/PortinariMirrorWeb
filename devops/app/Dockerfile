###
# Ambientum
#
# Repository:    PHP
# Image:         CLI/Base
# Version:       7.2.x
# Strategy:      PHP From PHP-Alpine Repository (CODECASTS) (https://php-alpine.codecasts.rocks)
# Base distro:   Alpine 3.7
#
FROM i386/alpine:3.7

# Repository/Image Maintainer
LABEL maintainer="Diego Hernandes <diego@hernandev.com>"

# Variables for enabling NewRelic
ENV FRAMEWORK=laravel \
    OPCACHE_MODE="normal" \
    XDEBUG_ENABLED=false \
    NR_ENABLED=false \
    NR_APP_NAME="" \
    NR_LICENSE_KEY="" \
    TERM=xterm-256color \
    COLORTERM=truecolor \
    COMPOSER_PROCESS_TIMEOUT=1200

# Add the ENTRYPOINT script
ADD start.sh /scripts/start.sh
ADD bashrc /home/ambientum/.bashrc

# Install PHP From DotDeb, Common Extensions, Composer and then cleanup
RUN echo "---> Enabling PHP-Alpine" && \
    #apk add --update wget && \
    #wget -O /etc/apk/keys/php-alpine.rsa.pub http://php.codecasts.rocks/php-alpine.rsa.pub && \
    #echo "@php http://php.codecasts.rocks/v3.7/php-7.2" >> /etc/apk/repositories && \
    apk add --update \
    curl \
    bash \
    fontconfig \
    libxrender \
    libxext \
    imagemagick \
    nano \
    vim \
    git \
    unzip \
    wget \
    make \
    sudo

RUN echo "---> Preparing and Installing PHP" && \
    apk add --update \
    php7 \
    php7-apcu \
    php7-bcmath \
    php7-bz2 \
    php7-calendar \
    php7-curl \
    php7-ctype \
    php7-exif \
    php7-fpm \
    php7-gd \
    php7-gmp \
    php7-iconv \
    php7-imagick \
    php7-imap \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-mysqlnd \
    php7-pdo_mysql \
    php7-mailparse \
    #php7-mongodb \
    php7-opcache \
    php7-pdo_pgsql \
    php7-pgsql \
    php7-posix \
    php7-redis \
    php7-soap \
    #php7-sodium \
    php7-sqlite3 \
    php7-pdo_sqlite \
    php7-xdebug \
    php7-xml \
    php7-xmlreader \
    php7-openssl \
    php7-phar \
    php7-zip \
    php7-zlib \
    php7-pcntl \
    php7-phpdbg
    #sudo ln -s /usr/bin/php7 /usr/bin/php && \
RUN echo "---> Installing Composer" && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN echo "---> Cleaning up" && \
    rm -rf /tmp/* && \
    echo "---> Adding the ambientum user" && \
    adduser -D -u 1000 ambientum && \
    mkdir -p /var/www/app && \
    chown -R ambientum:ambientum /var/www && \
    wget -O /tini https://github.com/krallin/tini/releases/download/v0.16.1/tini-static && \
    chmod +x /tini
RUN echo "---> Configuring PHP" && \
    echo "ambientum  ALL = ( ALL ) NOPASSWD: ALL" >> /etc/sudoers && \
    sed -i "/user = .*/c\user = ambientum" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/^group = .*/c\group = ambientum" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/listen.owner = .*/c\listen.owner = ambientum" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/listen.group = .*/c\listen.group = ambientum" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/listen = .*/c\listen = [::]:9000" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/;access.log = .*/c\access.log = /proc/self/fd/2" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/;clear_env = .*/c\clear_env = no" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/;catch_workers_output = .*/c\catch_workers_output = yes" /etc/php7/php-fpm.d/www.conf && \
    sed -i "/pid = .*/c\;pid = /run/php/php7.1-fpm.pid" /etc/php7/php-fpm.conf && \
    sed -i "/;daemonize = .*/c\daemonize = yes" /etc/php7/php-fpm.conf && \
    sed -i "/error_log = .*/c\error_log = /proc/self/fd/2" /etc/php7/php-fpm.conf && \
    sed -i "/post_max_size = .*/c\post_max_size = 1000M" /etc/php7/php.ini && \
    sed -i "/upload_max_filesize = .*/c\upload_max_filesize = 1000M" /etc/php7/php.ini && \
    #sed -i "/zend_extension=xdebug/c\;zend_extension=xdebug" /etc/php7/conf.d/00_xdebug.ini && \
    #echo "---> Adding Support for NewRelic" && \
    #mkdir /tmp/newrelic && \
    #cd /tmp/newrelic && \
    #wget -r -l1 -nd -A"linux-musl.tar.gz" https://download.newrelic.com/php_agent/release/ && \
    #gzip -dc newrelic*.tar.gz | tar xf - && \
    #cd newrelic-php5* && \
    #rm -f /usr/lib/php7/modules/newrelic.so && \
    #cp ./agent/x64/newrelic-20170718.so /usr/lib/php7/modules/newrelic.so && \
    #cp ./daemon/newrelic-daemon.x64 /usr/bin/newrelic-daemon && \
    #cp ./scripts/newrelic.ini.template /scripts/newrelic.ini && \
    #mkdir /var/log/newrelic && \
    #chown -R ambientum:ambientum /var/log/newrelic && \
    chown -R ambientum:ambientum /home/ambientum && \
    chmod +x /scripts/start.sh && \
    rm -rf /tmp/*

# Define the running user
USER ambientum

# Application directory
WORKDIR "/var/www/app"

# Environment variables
ENV PATH=/home/ambientum/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

# Define the entry point that tries to enable newrelic
ENTRYPOINT ["/tini", "--", "/scripts/start.sh"]

# As non daemon and single base image, it may be used as cli container
CMD ["/bin/bash"]
