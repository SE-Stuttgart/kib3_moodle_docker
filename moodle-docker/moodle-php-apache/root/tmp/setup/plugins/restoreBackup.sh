# TODO fetch backup online instead of from local file
cd /var/www/html/ && sudo -u www-data php ./admin/cli/restore_backup.php --file=/var/www/html/sicherung-moodle-kib3-zq.mbz --categoryid=1

# Absolute path to this script, e.g. /home/user/bin/foo.sh
SCRIPT=$(readlink -f "$0")
# Absolute path this script is in, thus /home/user/bin
SCRIPTPATH=$(dirname "$SCRIPT")

# Poll database for ID of restored course (once it is ready)
courseid="NULL";
while [courseid = "NULL" ]
do
    output=$(php $SCRIPTPATH/get_course_id.php)
    # Find the line with the string "COURSEID"
    line=$(grep "COURSEID" input_file.txt)
    # Extract the value after the equal sign
    courseid=$(echo "$line" | cut -d '=' -f2)
    echo "KURS"
    echo $courseid
done