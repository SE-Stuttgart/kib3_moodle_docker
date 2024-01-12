
# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

# Change directory permissions
echo "Setting directory permissons..."
echo "Done"

###
### Check if moodle is already installed - if not, install
###
if [ ! -f /var/www/html/moodle/config.php ];
then 
    ### Install moodle
    echo "Installing moodle..."

    sudo chmod +x /tmp/setup/moodle-docker-wait-for-it.sh 
    sudo -u www-data /tmp/setup/moodle-docker-wait-for-it.sh db:$MOODLE_DOCKER_DBPORT -- php /var/www/html/moodle/admin/cli/install.php --lang=de --chmod=777 --wwwroot=$MOODLE_SERVER_URL --dataroot=/var/www/html/moodledata --adminuser=$MOODLE_ADMIN_USER --adminpass=$MOODLE_ADMIN_PASSWORD --adminemail=$MOODLE_ADMIN_EMAIL --non-interactive --allow-unstable --agree-license --dbtype=$MOODLE_DOCKER_DBTYPE --dbhost="db" --dbport=$MOODLE_DOCKER_DBPORT  --dbname=$MOODLE_DOCKER_DBNAME --dbuser=$MOODLE_DOCKER_DBUSER --dbpass=$MOODLE_DOCKER_DBPASS --fullname=$MOODLE_FULL_NAME --shortname=$MOODLE_SHORT_NAME

    ###
    ### Configure moodle BEFORE IMPORTING COURSE
    ###

    # enable reverse proxy
    sed -i '/^$CFG->wwwroot.*/a $CFG->reverseproxy = true;' /var/www/html/moodle/config.php
    # enable ssl
    if [ "$MOODLE_SERVER_SSL" = true ];
    then
        sed -i '/^$CFG->wwwroot.*/a $CFG->sslproxy = true;' /var/www/html/moodle/config.php
    fi

    # increase limit of sections per course (required to restore backup)
    php /var/www/html/moodle/admin/cli/cfg.php --component=moodlecourse --name=maxsections --set=150

    # configure filters
    php /tmp/setup/plugins/configure_filters.php

    ###
    ### Restore course backup and store course id
    ###
    sh /tmp/setup/restoreBackup.sh
    php /tmp/setup/get_restored_course_id.php

    ###
    ### Webservices
    ###
    php /var/www/html/moodle/admin/cli/cfg.php --name=enablewebservices --set=1
    php /var/www/html/moodle/admin/cli/cfg.php --name=webserviceprotocols --set=rest
    php /tmp/setup/plugins/configure_webservices.php

    ###
    ### Configure Plugins that need to know course name (AFTER IMPORTING COURSE)
    ###
    sh /tmp/setup/plugins/configure.sh

    ###
    ### Setup CRON
    ###
    crontab -u www-data -l | echo "* * * * * /usr/local/bin/php /var/www/html/moodle/admin/cli/cron.php > /dev/null" | crontab -u www-data -
    service cron start

    if [ "$DEBUG" = "true"  ];
    then 
        cd /var/www/html/moodle && npm -g install grunt-cli
        cd /var/www/html/moodle && npm install grunt
        # we have to adapt the minimum node version to the official debian package sources
        search='"node": ">=16.14.0 <17"'
        replace='"node": ">=12.0 <17"'
        # disable js caching
        sed -i "s/$search/$replace/" /var/www/html/moodle/package.json
        target_line="\$CFG->admin\s*=\s*'admin';"
        new_line="\$CFG->cachejs = false;"
        sed -i "/$target_line/a $new_line" /var/www/html/moodle/config.php
    fi
else
    echo "Found moodle installation"
fi

###
### Delete caches
###
rm -rf /var/www/html/moodledata/cache/* 
rm -rf /var/www/html/moodledata/localcache/* 

###
### Start apache to serve moodle
### 
# apache2ctl -D FOREGROUND
php-fpm -D # run php-fpm in background
service nginx start
# nginx -g 'daemon off;';
tail -f /dev/null