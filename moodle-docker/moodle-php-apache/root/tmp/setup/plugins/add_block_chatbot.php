<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// get course
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
$course = $DB->get_record('course', array('id'=>$course_id));

// set course page context
require_once($CFG->libdir.'/blocklib.php');
$page = new moodle_page();
$page->set_course($course);

// add block to course pages (everywhere)
$page->blocks->add_region(BLOCK_POS_RIGHT, false);
$page->blocks->add_block('chatbot', BLOCK_POS_RIGHT, 1, true, '*');
print "Added chatbot block to course".$course->id."\n";
