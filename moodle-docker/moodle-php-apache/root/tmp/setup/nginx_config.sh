#!/usr/bin/env bash
echo "Setting up nginx..."

# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

# set server name (without http(s)):
export SERVER_NAME=${MOODLE_SERVER_URL//https:\/\//}
export SERVER_NAME=${SERVER_NAME//http:\/\//} 

if [ "$MOODLE_SERVER_SSL" = true ]; then
    echo "Setting up nginx for SSL"
    # remove default config (no ssl)
    rm /etc/nginx/sites-enabled/default
    
    # replace server name with environment variables
    envsubst < /etc/nginx/sites-enabled/ssl > /etc/nginx/sites-enabled/default
    # delete unsubstituted ssl config file
    # move certificate and key
    mv /etc/nginx/moodle_certificate.crt /etc/ssl/certs/moodle_certificate.crt
    mv /etc/nginx/moodle.key /etc/ssl/private/moodle.key
    echo "Done."
else
    echo "Setting up nginx without SSL"
    # replace server name with environment variables
    envsubst < /etc/nginx/sites-enabled/nossl > /etc/nginx/sites-enabled/default
    echo "Done."
fi

# remove obsolete ssl config
rm /etc/nginx/sites-enabled/ssl
rm /etc/nginx/sites-enabled/nossl