<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

$course_id = intval(file_get_contents("/tmp/setup/data/restored_course_info.txt"));

// activate all badges for the restored course
$counter = 0;
$badges = $DB->get_records('badge', array('courseid'=>$course_id));
foreach($badges as $badge) {
    $badge->status = 1;
    $DB->update_record('badge', $badge);
    $counter = $counter + 1;
}

print "\nACTIVATED " . $counter . " BADGES FOR COURSE " . $course_id;