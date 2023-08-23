# TODO fetch backup online instead of from local file

# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

# Restore backup
sudo chown www-data /tmp/setup/data/$COURSE_BACKUP_FILE
sudo -u www-data php /var/www/html/moodle/admin/cli/restore_backup.php --file=/tmp/setup/data/$COURSE_BACKUP_FILE --categoryid=1 > /tmp/setup/data/restored_course_info.txt

# Perform CRON jobs to fetch H5P libraries
sudo -u www-data php /var/www/html/moodle/admin/cli/scheduled_task.php --execute="\core\task\h5p_get_content_types_task" -f
sudo -u www-data php /var/www/html/moodle/admin/cli/cron.php 