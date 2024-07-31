FROM sheogorath15/kib3:webserver-base-v1
ARG TARGETPLATFORM && ENV TARGETPLATFORM=${TARGETPLATFORM:-linux/amd64}

ARG DEBUG
RUN [ ${DEBUG} = "true" ] && (apt update && apt install vim watchman -y) && \
      (curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash) \
    || true

# Setup the required extensions.
ARG DEBIAN_FRONTEND=noninteractive
COPY ./moodle-php-apache/root/tmp/setup/nginx_config.sh /tmp/setup/
ENV LD_LIBRARY_PATH /usr/local/instantclient
# Add configs
COPY ./moodle-php-apache/root/usr/local/etc/php/conf.d/docker-php-moodle.ini /usr/local/etc/php/conf.d/docker-php-moodle.ini
COPY ./config.env /tmp/setup/config.env
# Add ngingx configs & configure nginx 
COPY ./moodle-php-apache/root/etc/nginx /etc/nginx
RUN sh /tmp/setup/nginx_config.sh

# Download moodle
RUN source /tmp/setup/config.env &&\
    git clone --depth=1 --branch $MOODLE_VERSION https://github.com/moodle/moodle.git /var/www/html/moodle && \
    mkdir /var/www/html/moodledata && chown www-data /var/www/html/moodledata && \
    chmod -R 777 /var/www/html/moodle && \
    chmod -R 777 /var/www/html/moodledata

# Copy eslintrc (ONLY FOR DEVELOPMENT)
COPY .eslintrc /var/www/html/moodle/.eslintrc

# Download plugins
COPY ./moodle-php-apache/root/tmp/setup/plugins/add_block_booksearch.php \
    ./moodle-php-apache/root/tmp/setup/plugins/add_block_chatbot.php \
    ./moodle-php-apache/root/tmp/setup/plugins/configure_filters.php \
    ./moodle-php-apache/root/tmp/setup/plugins/configure_webservices.php \ 
    ./moodle-php-apache/root/tmp/setup/plugins/configure_badges.php \
    ./moodle-php-apache/root/tmp/setup/plugins/disable_usertours.php \
    ./moodle-php-apache/root/tmp/setup/plugins/download.sh \ 
    ./moodle-php-apache/root/tmp/setup/plugins/configure.sh \ 
    /tmp/setup/plugins/
COPY ./moodle-php-apache/root/tmp/setup/get_restored_course_id.php \
    /tmp/setup/
RUN source /tmp/setup/config.env && sh /tmp/setup/plugins/download.sh

# Add backup
COPY ./moodle-php-apache/root/tmp/setup/restoreBackup.sh /tmp/setup/restoreBackup.sh
ARG COURSE_BACKUP_FILE_ARG
COPY ./${COURSE_BACKUP_FILE_ARG} /tmp/setup/data/${COURSE_BACKUP_FILE_ARG}

# Add moodle install file
COPY ./moodle-php-apache/root/tmp/setup/install_moodle.sh /tmp/setup/install_moodle.sh