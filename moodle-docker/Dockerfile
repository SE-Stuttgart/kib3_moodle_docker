FROM php:8.1-fpm-bullseye 
ARG TARGETPLATFORM && ENV TARGETPLATFORM=${TARGETPLATFORM:-linux/amd64}

# Install some packages that are useful within the images.
# Cleanup afterwards.
RUN apt-get update && apt-get install -y \
        git \
        sudo \
        vim \
        nginx &&\
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
# Add ngingx config & start service
COPY ./moodle-php-apache/root/etc/nginx /etc/nginx

# Download moodle
COPY ./config.env /tmp/setup/config.env
RUN source /tmp/setup/config.env &&\
    git clone --branch $MOODLE_VERSION https://github.com/moodle/moodle.git /var/www/html/moodle && \
    mkdir /var/www/html/moodledata && chown www-data /var/www/html/moodledata && \
    chmod -R 777 /var/www/html/moodle && \
    chmod -R 755 /var/www/html/moodledata

# Copy eslintrc (ONLY FOR DEVELOPMENT)
COPY .eslintrc /var/www/html/moodle/.eslintrc

# Download plugins
COPY ./moodle-php-apache/root/tmp/setup/plugins/add_block_slidefinder.php \
    ./moodle-php-apache/root/tmp/setup/plugins/add_block_chatbot.php \
    ./moodle-php-apache/root/tmp/setup/plugins/configure_filters.php \
    ./moodle-php-apache/root/tmp/setup/plugins/configure_webservices.php \ 
    ./moodle-php-apache/root/tmp/setup/plugins/download.sh \ 
    ./moodle-php-apache/root/tmp/setup/plugins/configure.sh \ 
    /tmp/setup/plugins/
RUN source /tmp/setup/config.env && sh /tmp/setup/plugins/download.sh

# Add backup
COPY ./moodle-php-apache/root/tmp/setup/restoreBackup.sh /tmp/setup/restoreBackup.sh
ARG COURSE_BACKUP_FILE_ARG
COPY ./${COURSE_BACKUP_FILE_ARG} /tmp/setup/data/${COURSE_BACKUP_FILE_ARG}

# Add moodle install file
COPY ./moodle-php-apache/root/tmp/setup/install_moodle.sh /tmp/setup/install_moodle.sh
