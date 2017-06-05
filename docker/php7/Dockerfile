FROM debian:jessie

## Install tools
RUN apt-get update --fix-missing \
    && apt-get upgrade -y \
    && apt-get dist-upgrade -y \
    && apt-get install -y software-properties-common wget sudo

# Add PHP PPA
RUN echo 'deb http://packages.dotdeb.org jessie all' >> /etc/apt/sources.list \
    && wget https://www.dotdeb.org/dotdeb.gpg \
    && sudo apt-key add dotdeb.gpg

## Install tools
RUN apt-get update --fix-missing \
    && apt-get upgrade -y \
    && apt-get dist-upgrade -y \
    && apt-get install -y \
      php7.0 \
      php7.0-cli \
      php7.0-bcmath \
      php7.0-curl \
      php7.0-fpm \
      php7.0-gd \
      php7.0-intl \
      php7.0-imap \
      php7.0-json \
      php7.0-ldap \
      php7.0-mcrypt \
      php7.0-mbstring \
      php7.0-mysql \
      php7.0-soap \
      php7.0-xml \
      php7.0-xsl \
      php7.0-xmlrpc \
      php7.0-zip \
      php7.0-xdebug \
      graphicsmagick \
      bzip2 \
    && apt-get clean

ADD files/pool.conf            /etc/php/7.0/fpm/pool.d/zzz.conf
ADD files/xdebug.ini           /etc/php/7.0/mods-available/xdebug.ini
ADD files/php.ini              /etc/php/7.0/fpm/conf.d/30-user.ini

# Configure php-fpm
RUN mkdir -p /run/php \
    && usermod -u 1000 www-data

CMD ["php-fpm7.0", "-F", "-R"]
