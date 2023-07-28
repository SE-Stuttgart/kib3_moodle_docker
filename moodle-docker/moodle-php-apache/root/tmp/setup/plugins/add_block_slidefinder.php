<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// get course
$courses = $DB->get_records('course');
$course = NULL;

$f_restore_info = fopen("/tmp/setup/data/restored_course_info.txt", "r");
if ($f_restore_info) {
    while (($line = fgets($f_restore_info)) !== false) {
        if (strpos($line, 'ID') !== false) {
            preg_match_all('/\d+/', $line, $matches);
            $course_id = $matches[0][0];
            echo "Slidefinder: Course id: " . $course_id . "\n";
        }
    }
    fclose($f_restore_info);
} else {
    echo "Slidefinder: Failed to open the file /tmp/setup/data/restored_course_info.txt\n";
}

foreach($courses as $course_candidate) {
    if($course_candidate->id == $course_id) {
        $course = $course_candidate;
        break;
    }
}

// set course page context
require_once($CFG->libdir.'/blocklib.php');
$pagetypepattern = 'course-view-*';
$page = new moodle_page();
$page->set_course($course);

// add block to course pages
$page->blocks->add_region(BLOCK_POS_RIGHT, false);
$page->blocks->add_block('slidefinder', BLOCK_POS_RIGHT, true, $pagetypepattern);
print "Added slidefinder block to course".$course->id."\n";