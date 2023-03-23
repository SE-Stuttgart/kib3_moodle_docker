if [ -e /var/www/html/config.php ]
then
    echo "Moodle already installed (config.php already exists)"
else
    # Load environment variables that are otherwise not visible inside this script
    source /tmp/setup/config.env 

    echo "Downlading moodle..."
    # Download only branch matching specified moodle version 
    git clone --branch $MOODLE_VERSION https://github.com/moodle/moodle.git /var/www/html

    # Download Plugins
    sh /tmp/setup/plugins/download.sh

    # Install moodle
    echo "Installing moodle..."
    cd /tmp/setup/ && sudo chmod +x moodle-docker-wait-for-it.sh 
    cd /tmp/setup && sudo -u www-data ./moodle-docker-wait-for-it.sh db:$MOODLE_DOCKER_DBPORT -- php /var/www/html/admin/cli/install.php --lang=de --chmod=2775 --wwwroot="http://${MOODLE_DOCKER_WEB_PORT}" --dataroot=/var/www/moodledata --adminuser=$MOODLE_ADMIN_USER --adminpass=$MOODLE_ADMIN_PASSWORD --adminemail=$MOODLE_ADMIN_EMAIL --non-interactive --allow-unstable --agree-license --dbtype=$MOODLE_DOCKER_DBTYPE --dbhost="db" --dbport=3306  --dbname=$MOODLE_DOCKER_DBNAME --dbuser=$MOODLE_DOCKER_DBUSER --dbpass=$MOODLE_DOCKER_DBPASS --fullname=$MOODLE_FULL_NAME --shortname=$MOODLE_SHORT_NAME
    
    # Configure Plugins
    sh /tmp/setup/plugins/configure.sh
    
    # TODO donwload backup from URL?
    # Restore course backup
    sh /tmp/setup/restoreBackup.sh
fi

apache2ctl -D FOREGROUND

