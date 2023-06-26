# TODO fetch backup online instead of from local file

# Load environment variables that are otherwise not visible inside this script
source /tmp/setup/config.env 

# Restore backup
sudo chown www-data /tmp/setup/data/$COURSE_BACKUP_FILE
cd /var/www/html/ && sudo -u www-data php ./admin/cli/restore_backup.php --file=/tmp/setup/data/$COURSE_BACKUP_FILE --categoryid=1
