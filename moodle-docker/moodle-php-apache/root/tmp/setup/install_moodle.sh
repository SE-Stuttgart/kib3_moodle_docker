
# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

# Change directory permissions
echo "Setting directory permissons..."
sudo chmod -R 777 /var/www/moodledata
sudo chmod 755 /var/www/moodledata
echo "Done"

###
### Check if moodle is already installed - if not, install
###
if [ ! -f /var/www/html/config.php ]
then 
    ### Install moodle
    echo "Installing moodle..."

    sudo chmod +x /tmp/setup/moodle-docker-wait-for-it.sh 
    sudo -u www-data /tmp/setup/moodle-docker-wait-for-it.sh db:$MOODLE_DOCKER_DBPORT -- php /var/www/html/admin/cli/install.php --lang=de --chmod=2775 --wwwroot="http://${MOODLE_DOCKER_URL}" --dataroot=/var/www/moodledata --adminuser=$MOODLE_ADMIN_USER --adminpass=$MOODLE_ADMIN_PASSWORD --adminemail=$MOODLE_ADMIN_EMAIL --non-interactive --allow-unstable --agree-license --dbtype=$MOODLE_DOCKER_DBTYPE --dbhost="db" --dbport=$MOODLE_DOCKER_DBPORT  --dbname=$MOODLE_DOCKER_DBNAME --dbuser=$MOODLE_DOCKER_DBUSER --dbpass=$MOODLE_DOCKER_DBPASS --fullname=$MOODLE_FULL_NAME --shortname=$MOODLE_SHORT_NAME

    ###
    ### Configure moodle BEFORE IMPORTING COURSE
    ###

    # increase limit of sections per course (required to restore backup)
    php admin/cli/cfg.php --component=moodlecourse --name=maxsections --set=150

    # configure filters
    php /tmp/setup/plugins/configure_filters.php

    # webservice
    php admin/cli/cfg.php --name=enablewebservices --set=1
    php admin/cli/cfg.php --name=webserviceprotocols --set=rest
    php /tmp/setup/plugins/configure_webservices.php

    # TODO configure ice cream game (+ web services)

    ###
    ### Restore course backup
    ###
    sh /tmp/setup/restoreBackup.sh

    ###
    ### Configure Plugins that need to know course name (AFTER IMPORTING COURSE)
    ###
    sh /tmp/setup/plugins/configure.sh
fi

###
### Start apache to serve moodle
### 
apache2ctl -D FOREGROUND

