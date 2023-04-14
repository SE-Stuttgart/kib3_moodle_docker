 # Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

# Absolute path to this script, e.g. /home/user/bin/foo.sh
SCRIPT=$(readlink -f "$0")
# Absolute path this script is in, thus /home/user/bin
SCRIPTPATH=$(dirname "$SCRIPT")

# Poll database for ID of restored course (once it is ready)
courseid="NULL";
while [ "$courseid" = "NULL" ]
do
    # Find the line with the string "COURSEID"
    line=$(php $SCRIPTPATH/get_course_id.php | grep "COURSEID")
    # Extract the value after the equal sign
    courseid=$(echo "$line" | cut -d '=' -f2)
    echo "KURS"
    echo $courseid
done

if [ "$PLUGIN_AUTOCOMPLETE" = true ]; then
    # set course id (get course id from backup) - get from database with backup course id
    echo "Configuring plugin autocomplete..."
    # Set course id for autocomplete plugin
    php /var/www/html/admin/cli/cfg.php --component=local_autocompleteactivities --name=courseids --set=$courseid
fi
