# TODO fetch backup online instead of from local file

# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

# Restore ZQ backup 
if [ -f "/tmp/setup/data/$COURSE_BACKUP_FILE_ZQ" ]; then
    sudo chown www-data "/tmp/setup/data/$COURSE_BACKUP_FILE_ZQ"
    sudo -u www-data php /var/www/html/moodle/admin/cli/restore_backup.php --file=/tmp/setup/data/$COURSE_BACKUP_FILE_ZQ --categoryid=1
fi

# Restore DQR5 backup 
if [ -f "/tmp/setup/data/$COURSE_BACKUP_FILE_DQR5" ]; then
    sudo chown www-data "/tmp/setup/data/$COURSE_BACKUP_FILE_DQR5"
    sudo -u www-data php /var/www/html/moodle/admin/cli/restore_backup.php --file=/tmp/setup/data/$COURSE_BACKUP_FILE_DQR5 --categoryid=1
fi

# Perform CRON jobs to fetch H5P libraries
sudo -u www-data php /var/www/html/moodle/admin/cli/scheduled_task.php --execute="\core\task\h5p_get_content_types_task" -f
sudo -u www-data php /var/www/html/moodle/admin/cli/cron.php 