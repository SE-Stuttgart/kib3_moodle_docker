# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env

# Setup php and moodle memory limits
envsubst < /usr/local/etc/php/conf.d/docker-php-moodle.ini | sponge /usr/local/etc/php/conf.d/docker-php-moodle.ini
php /var/www/html/moodle/admin/cli/cfg.php --name=extramemorylimit --set=$MOODLE_SERVER_MEMORY_LIMIT

# Activate all badges
php /tmp/setup/plugins/configure_badges.php

# Disable user tours
php /tmp/setup/plugins/disable_usertours.php

# Poll database for ID of restored course (once it is ready)
courseid=$(cat /tmp/setup/data/restored_course_info.txt)
echo "KURS: ${courseid}"

if [ "$PLUGIN_AUTOCOMPLETE" = true ]; then
    # set course id (get course id from backup) - get from database with backup course id
    echo "Configuring plugin autocomplete..."
    # Set course id for autocomplete plugin
    php /var/www/html/moodle/admin/cli/cfg.php --component=local_autocompleteactivities --name=courseids --set=$courseid
    echo "Done."
fi

if [ "$PLUGIN_SLIDEFINDER" = true ]; then
    echo "Configuring plugin slidefinder..."
    # this will add the slidefinder block to the right for all course pages
    php /tmp/setup/plugins/add_block_slidefinder.php
    echo "Done."
fi

if [ "$PLUGIN_CHATBOT" = true ]; then
    echo "Configuring plugin chatbot..."
    # this will add the chatbot block to all pages
    php /tmp/setup/plugins/add_block_chatbot.php
fi
