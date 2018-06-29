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
LABEL maintainer="Pedro Malta <pedromalta@gmail.com>"

ENV FRAMEWORK=laravel \
    OPCACHE_MODE="normal" \
    XDEBUG_ENABLED=false \
    TERM=xterm-256color \
    COLORTERM=truecolor \
    COMPOSER_PROCESS_TIMEOUT=1200

# Copy nginx and entry script
COPY nginx.conf /etc/nginx/nginx.conf
COPY ssl.conf /etc/nginx/ssl.conf
COPY sites /etc/nginx/sites
COPY start.sh  /home/ambientum/start.sh
COPY bashrc /home/ambientum/.bashrc

# Install PHP From DotDeb, Common Extensions, Composer and then cleanup
RUN echo "---> Enabling PHP-Alpine" && \
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
    tini \
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
    php7-phpdbg \
    php7-fileinfo \
    php7-xmlwriter \
    php7-tokenizer

RUN echo "---> Installing Composer" && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN echo "---> Cleaning up" && \
    rm -rf /tmp/* && \
    echo "---> Adding the ambientum user" && \
    adduser -D -u 1000 ambientum && \
    mkdir -p /var/www/app && \
    chown -R ambientum:ambientum /var/www
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
    sed -i "/upload_max_filesize = .*/c\upload_max_filesize = 1000M" /etc/php7/php.ini


# Install nginx from dotdeb (already enabled on base image)
RUN echo "--> Installing Nginx" && \
    apk add --update nginx openssl && \
    rm -rf /tmp/* /var/tmp/* /usr/share/doc/* && \
    echo "--> Fixing permissions" && \
    mkdir /var/tmp/nginx && \
    mkdir /var/run/nginx && \
    mkdir /home/ambientum/ssl && \
    chown -R ambientum:ambientum /var/tmp/nginx && \
    chown -R ambientum:ambientum /var/run/nginx && \
    chown -R ambientum:ambientum /var/log/nginx && \
    chown -R ambientum:ambientum /var/lib/nginx && \
    chmod +x /home/ambientum/start.sh && \
    chown -R ambientum:ambientum /home/ambientum

# Define the running user
USER ambientum

# Pre generate some SSL
# YOU SHOULD REPLACE WITH YOUR OWN CERT.
RUN openssl req -x509 -nodes -days 3650 \
   -newkey rsa:2048 -keyout /home/ambientum/ssl/nginx.key \
   -out /home/ambientum/ssl/nginx.crt -subj "/C=AM/ST=Ambientum/L=Ambientum/O=Ambientum/CN=*.dev" && \
   openssl dhparam -out /home/ambientum/ssl/dhparam.pem 2048

# Environment variables
ENV PATH=/home/ambientum/.composer/vendor/bin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

# Application directory
WORKDIR "/var/www/app"

# Expose webserver port
EXPOSE 8080

# Starts a single shell script that puts php-fpm as a daemon and nginx on foreground
CMD ["/home/ambientum/start.sh"]
