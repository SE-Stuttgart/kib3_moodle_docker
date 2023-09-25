#!/usr/bin/env bash

# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

echo "Setting up nginx..."
if [ "$MOODLE_SERVER_SSL" = true ]; then
    echo "Setting up nginx for SSL"
    # remove default config (no ssl)
    rm /etc/nginx/sites-enabled/default
    # set server name (without http(s)):
    export SERVER_NAME=${MOODLE_SERVER_URL//https:\/\//}
    export SERVER_NAME=${SERVER_NAME//http:\/\//} 
    # replace server name with environment variables
    envsubst < /etc/nginx/sites-enabled/ssl > /etc/nginx/sites-enabled/default
    # delete unsubstituted ssl config file
    rm /etc/nginx/sites-enabled/ssl
    # move certificate and key
    mv /etc/nginx/moodle_certificate.crt /etc/ssl/moodle_certificate.crt
    mv /etc/nginx/moodle.key /etc/ssl/moodle.key
    echo "Done."
else
    echo "Setting up nginx without SSL"
    # remove ssl config
    rm /etc/nginx/sites-enabled/ssl
fi
