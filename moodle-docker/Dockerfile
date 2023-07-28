FROM php:8.1-apache-bullseye 
ARG TARGETPLATFORM && ENV TARGETPLATFORM=${TARGETPLATFORM:-linux/amd64}


# Install some packages that are useful within the images.
# Cleanup afterwards.
RUN apt-get update && apt-get install -y \
        git \
        sudo \
        vim &&\
    rm -rf /var/lib/apt/lists/* && \
    rm /bin/sh && ln -s /bin/bash /bin/sh


# Setup the required extensions.
ARG DEBIAN_FRONTEND=noninteractive
COPY ./moodle-php-apache/root/tmp/setup/php-extensions.sh \
     ./moodle-php-apache/root/tmp/setup/oci8-extension.sh \
     ./moodle-php-apache/root/tmp/setup/moodle-docker-wait-for-it.sh \
     ./moodle-php-apache/root/tmp/setup/sqlsrv-extension.sh \
     /tmp/setup/
# Fix the original permissions of /tmp, the PHP default upload tmp dir
# Then, install php extensions
RUN chmod 777 /tmp && chmod +t /tmp && \ 
    /tmp/setup/php-extensions.sh 
    # && /tmp/setup/oci8-extension.sh && \
ENV LD_LIBRARY_PATH /usr/local/instantclient
# Add php config
COPY ./moodle-php-apache/root/usr/local/etc/php/conf.d/docker-php-moodle.ini /usr/local/etc/php/conf.d/docker-php-moodle.ini


# Download moodle
COPY ./config.env /tmp/setup/config.env
RUN source /tmp/setup/config.env &&\
    git clone --branch $MOODLE_VERSION https://github.com/moodle/moodle.git /var/www/html && \
    mkdir /var/www/moodledata && chown www-data /var/www/moodledata

# Download plugins
COPY ./moodle-php-apache/root/tmp/setup/plugins /tmp/setup/plugins
RUN source /tmp/setup/config.env && sh /tmp/setup/plugins/download.sh

# Add backup
COPY ./moodle-php-apache/root/tmp/setup/restoreBackup.sh /tmp/setup/restoreBackup.sh
COPY ./$COURSE_BACKUP_FILE /tmp/setup/data/$COURSE_BACKUP_FILE

# Add moodle install file
COPY ./moodle-php-apache/root/tmp/setup/install_moodle.sh /tmp/setup/install_moodle.sh