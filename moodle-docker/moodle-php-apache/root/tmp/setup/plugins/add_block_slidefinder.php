<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// get course
$course_id = intval(file_get_contents("/tmp/setup/data/restored_course_info.txt"));
$course = $DB->get_record('course', array('id'=>$course_id));

// set course page context
require_once($CFG->libdir.'/blocklib.php');
$page = new moodle_page();
$page->set_course($course);

// add block to course pages
$page->blocks->add_region(BLOCK_POS_RIGHT, false);
$page->blocks->add_block('slidefinder', BLOCK_POS_RIGHT, 1, true); // show on frontpage and all subcontexts
print "Added slidefinder block to course".$course->id."\n";
