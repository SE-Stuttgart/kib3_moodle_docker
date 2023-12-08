<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

$f_restore_info = fopen("/tmp/setup/data/restored_course_info.txt", "r");
if ($f_restore_info) {
    while (($line = fgets($f_restore_info)) !== false) {
        if (strpos($line, 'ID') !== false) {
            preg_match_all('/\d+/', $line, $matches);
            $course_id = $matches[0][0];
            echo "Chatbot: Course id: " . $course_id . "\n";
        }
    }
    fclose($f_restore_info);
} else {
    echo "Chatbot: Failed to open the file /tmp/setup/data/restored_course_info.txt\n";
}

// activate all badges for the restored course
$counter = 0;
$badges = $DB->get_records('badges', array('courseid'=>$course_id));
foreach($badges as $badge) {
    $badge->status = 1;
    $DB->update_record('badges', $badge);
    $counter = $counter + 1;
}

print "\nACTIVATED " . $counter . " BADGES FOR COURSE " . $course_id;