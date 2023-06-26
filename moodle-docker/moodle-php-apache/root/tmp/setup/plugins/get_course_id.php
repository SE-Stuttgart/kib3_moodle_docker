<?php
define('CLI_SCRIPT', true);

echo '=== Course ID Finder ===';

// Extract backup main xml file from backup archive
$backup_path = '/tmp/setup/data/' . getenv('COURSE_BACKUP_FILE');
$contents = file_get_contents('phar://' . $backup_path . '/moodle_backup.xml');

// Read course name from backup main xml file
$xml=simplexml_load_string($contents);
$course_full_name = $xml->information->original_course_fullname;
echo "Found course '" . $course_full_name . "' in backup";

$configfile = '/var/www/html/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();
$courses = $DB->get_records('course');
$courseid = "NULL";
foreach($courses as $course) {  
    if($course->fullname == $course_full_name) {
        $courseid = $course->id;
    }
}
print "\nCOURSEID=".$courseid."\n";
