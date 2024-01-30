<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

$_likesql_coursename = $DB->sql_like('fullname', ':coursename');
$course = $DB->get_record_sql("SELECT * FROM {course} WHERE $_likesql_coursename",
                               array(
                                "coursename" => "%Zusatzqualifikation KI und Maschinelles Lernen%"
                               ));
echo "\nRESTORED COURSE ID: " . $course->id;
file_put_contents("/tmp/setup/data/restored_course_info.txt", strval($course->id));
