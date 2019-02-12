FROM ubuntu:18.04

# NON INTERACTIVE MODE
ENV DEBIAN_FRONTEND=noninteractive

RUN apt update -qq && apt -yqq upgrade

RUN apt install -yq --no-install-recommends \
    apt-utils \
    curl \
    git \
    nano \
    openssl \
    graphicsmagick \
    imagemagick \
    ghostscript \
    mysql-client \
    iputils-ping \
    sqlite3 \
    ca-certificates \
    zip \
    unzip \
    gcc \
    make \
    autoconf \
    libc-dev \
    pkg-config \
    apache2 \
    libapache2-mod-php7.2 \
    locales

RUN locale-gen en_US.UTF-8 en_GB.UTF-8 de_DE.UTF-8 es_ES.UTF-8 \
    fr_FR.UTF-8 it_IT.UTF-8 km_KH sv_SE.UTF-8 fi_FI.UTF-8 ; \
    update-locale


RUN apt-get install -yq --no-install-recommends \
    php7.2-cli \
    php7.2-json \
    php7.2-curl \
    php7.2-fpm \
    php7.2-gd \
    php7.2-ldap \
    php7.2-mbstring \
    php7.2-mysql \
    php7.2-soap \
    php7.2-sqlite3 \
    php7.2-xml \
    php7.2-zip \
    php7.2-intl \
    php-imagick

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer



# COPY APACHE2 CONFIG
RUN mkdir /var/apache_logs
COPY ./000-default.conf /etc/apache2/sites-enabled/000-default.conf

# Add Site user and group
RUN groupadd -g 1000 site; \
    adduser --gecos "First Last,RoomNumber,WorkPhone,HomePhone" --home /home/site --uid 1000 --ingroup site --disabled-login --disabled-password site; \
    usermod -a -G sudo site

# Changing Apache User and PHP-FPM User
RUN sed -rie 's|export APACHE_RUN_USER=.*|export APACHE_RUN_USER=site|g' /etc/apache2/envvars; \
    sed -rie 's|export APACHE_RUN_GROUP=.*|export APACHE_RUN_GROUP=site|g' /etc/apache2/envvars; \
    sed -ie 's/www-data/site/g' /etc/php/7.2/fpm/pool.d/www.conf

COPY ./php.ini /etc/php/7.2/apache2/php.ini

# Enable Php-Fpm Conf
RUN a2enconf php7.2-fpm ; \
    a2enmod actions rewrite headers; \
    chown -R site:site /usr/lib/cgi-bin

RUN chown -R site:site /var/www/html

WORKDIR /var/www/html

RUN rm index.html

EXPOSE 80

# By default, simply start apache.
CMD apache2ctl -D FOREGROUND
