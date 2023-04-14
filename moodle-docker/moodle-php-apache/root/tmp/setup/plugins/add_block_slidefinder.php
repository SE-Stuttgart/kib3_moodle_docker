<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// get course
$courses = $DB->get_records('course');
$course = NULL;
foreach($courses as $course_candidate) {
    if($course_candidate->fullname == "Zusatzqualifikation KI und Maschinelles Lernen") {
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