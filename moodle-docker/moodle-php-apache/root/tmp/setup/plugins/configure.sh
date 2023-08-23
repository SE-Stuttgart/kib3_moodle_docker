# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env

# Poll database for ID of restored course (once it is ready)
courseid="NULL";
while [ "$courseid" = "NULL" ]
do
    # Find the line with the string "COURSEID"
    line=$(cat /tmp/setup/data/restored_course_info.txt | grep "ID")
    # Extract the value after the equal sign, remove equal characters, trim spaces with xargs
    courseid=$(echo "$line" | cut -d ':' -f2 | cut -d '=' -f1 | xargs)
    echo "KURS: ${courseid}"
done

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
