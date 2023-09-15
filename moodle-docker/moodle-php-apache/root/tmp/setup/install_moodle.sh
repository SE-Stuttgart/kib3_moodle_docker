
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
    sudo -u www-data /tmp/setup/moodle-docker-wait-for-it.sh db:$MOODLE_DOCKER_DBPORT -- php /var/www/html/moodle/admin/cli/install.php --lang=de --chmod=777 --wwwroot="http://127.0.0.1:8000" --dataroot=/var/www/html/moodledata --adminuser=$MOODLE_ADMIN_USER --adminpass=$MOODLE_ADMIN_PASSWORD --adminemail=$MOODLE_ADMIN_EMAIL --non-interactive --allow-unstable --agree-license --dbtype=$MOODLE_DOCKER_DBTYPE --dbhost="db" --dbport=$MOODLE_DOCKER_DBPORT  --dbname=$MOODLE_DOCKER_DBNAME --dbuser=$MOODLE_DOCKER_DBUSER --dbpass=$MOODLE_DOCKER_DBPASS --fullname=$MOODLE_FULL_NAME --shortname=$MOODLE_SHORT_NAME

    ###
    ### Configure moodle BEFORE IMPORTING COURSE
    ###

    # enable reverse proxy
    sed -i '/^$CFG->wwwroot.*/a $CFG->reverseproxy = true;' /var/www/html/moodle/config.php

    # increase limit of sections per course (required to restore backup)
    php /var/www/html/moodle/admin/cli/cfg.php --component=moodlecourse --name=maxsections --set=150

    # configure filters
    php /tmp/setup/plugins/configure_filters.php

    # webservice
    php /var/www/html/moodle/admin/cli/cfg.php --name=enablewebservices --set=1
    php /var/www/html/moodle/admin/cli/cfg.php --name=webserviceprotocols --set=rest
    php /tmp/setup/plugins/configure_webservices.php

    ###
    ### Restore course backup
    ###
    sh /tmp/setup/restoreBackup.sh

    ###
    ### Configure Plugins that need to know course name (AFTER IMPORTING COURSE)
    ###
    sh /tmp/setup/plugins/configure.sh
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