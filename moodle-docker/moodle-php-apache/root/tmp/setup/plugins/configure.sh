 # Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

if [ $PLUGIN_AUTOCOMPLETE  = true ]; then
    # set course id (get course id from backup) - get from database with backup course id
    echo "Configuring plugin autocomplete..."
    
    # Absolute path to this script, e.g. /home/user/bin/foo.sh
    SCRIPT=$(readlink -f "$0")
    # Absolute path this script is in, thus /home/user/bin
    SCRIPTPATH=$(dirname "$SCRIPT")

    # Poll database for ID of restored course (once it is ready)
    courseid="NULL";
    while [ "$courseid" = "NULL" ];
    do
        echo "Waiting for course backup to restore..."
        output=$(php $SCRIPTPATH/get_course_id.php)
        # Find the line with the string "COURSEID"
        line=$(echo "$output" | grep "COURSEID")
        # Extract the value after the equal sign
        courseid=$(echo "$line" | cut -d '=' -f2)
        # echo $courseid
        sleep 5 # wait in case course backup is not done yet and repeat
    done

    # Set course id for autocomplete plugin
    php admin/cli/cfg.php --component=local_autocompleteactivities --name=courseids --set=$courseid
fi