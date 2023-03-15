if [ -e /var/www/html/config.php ]
then
    echo "Moodle already installed (config.php already exists)"
else
    echo "Installing moodle..."
    # Load environment variables that are otherwise not visible inside this script
    source /tmp/setup/config.env 
    # cd /var/www/html && sudo -u www-data php /var/www/html/admin/cli/install.php --lang=de --chmod=2775 --wwwroot="http://${MOODLE_DOCKER_WEB_PORT}" --dataroot=/var/www/moodledata --adminuser="testadmin" --adminpass="ichbinadmin" --adminemail="test@test.test" --non-interactive --allow-unstable --agree-license --dbtype=$MOODLE_DOCKER_DBTYPE --dbhost="db" --dbport=3306  --dbname=$MOODLE_DOCKER_DBNAME --dbuser=$MOODLE_DOCKER_DBUSER --dbpass=$MOODLE_DOCKER_DBPASS --fullname="FullTestMoodle" --shortname="TestMoodle"
    cd /tmp/setup/ && sudo chmod +x moodle-docker-wait-for-it.sh 
    cd /tmp/setup && sudo -u www-data ./moodle-docker-wait-for-it.sh db:3306 -- php /var/www/html/admin/cli/install.php --lang=de --chmod=2775 --wwwroot="http://${MOODLE_DOCKER_WEB_PORT}" --dataroot=/var/www/moodledata --adminuser="testadmin" --adminpass="ichbinadmin" --adminemail="test@test.test" --non-interactive --allow-unstable --agree-license --dbtype=$MOODLE_DOCKER_DBTYPE --dbhost="db" --dbport=3306  --dbname=$MOODLE_DOCKER_DBNAME --dbuser=$MOODLE_DOCKER_DBUSER --dbpass=$MOODLE_DOCKER_DBPASS --fullname="FullTestMoodle" --shortname="TestMoodle"
    # TODO call plugin install script
    # TODO call data load script
fi
apache2ctl -D FOREGROUND

