<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// list of existing backup files
$backup_files = [];
$path_zq = "/tmp/setup/data/restored_course_info_zq.txt";
$path_dqr5 = "/tmp/setup/data/restored_course_info_dqr5.txt";
if (file_exists($path_zq)) {
    $backup_files["zq"] = $path_zq;
}
if (file_exists($path_dqr5)) {
    $backup_files["dqr5"] = $path_dqr5;
}
foreach($backup_files as $backup_file) {
    // get course id
    $course_id = intval(file_get_contents($backup_file));

    // activate all badges for the restored course
    $counter = 0;
    $badges = $DB->get_records('badge', array('courseid'=>$course_id));
    foreach($badges as $badge) {
        $badge->status = 1;
        $DB->update_record('badge', $badge);
        $counter = $counter + 1;
    }

    print "\nACTIVATED " . $counter . " BADGES FOR COURSE " . $course_id;
}