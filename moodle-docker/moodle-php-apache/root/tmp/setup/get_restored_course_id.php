<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

$course = $DB->get_record('course', array('fullname' => "KI B3 Zusatzqualifikation KI und Maschinelles Lernen Version 2.0"));
echo "\nRESTORED COURSE ID: " . $course->id;
file_put_contents("/tmp/setup/data/restored_course_info.txt", strval($course->id));
