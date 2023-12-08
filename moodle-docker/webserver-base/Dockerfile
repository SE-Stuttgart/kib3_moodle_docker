FROM php:8.1-fpm-bullseye 
ARG TARGETPLATFORM && ENV TARGETPLATFORM=${TARGETPLATFORM:-linux/amd64}

# Install some packages that are useful within the images.
# Cleanup afterwards.
RUN apt-get update && apt-get install -y \
        git \
        sudo \
        nginx \
        gettext-base \
        cron \
        moreutils && \
    rm -rf /var/lib/apt/lists/* && \
    rm /bin/sh && ln -s /bin/bash /bin/sh

# Setup the required extensions.
ARG DEBIAN_FRONTEND=noninteractive
COPY ./root/tmp/setup/php-extensions.sh \
     ./root/tmp/setup/oci8-extension.sh \
     ./root/tmp/setup/moodle-docker-wait-for-it.sh \
     ./root/tmp/setup/sqlsrv-extension.sh \
     /tmp/setup/
# Fix the original permissions of /tmp, the PHP default upload tmp dir
# Then, install php extensions
RUN chmod -R 777 /tmp && chmod +t /tmp && \ 
    /tmp/setup/php-extensions.sh 
    # && /tmp/setup/oci8-extension.sh && \
