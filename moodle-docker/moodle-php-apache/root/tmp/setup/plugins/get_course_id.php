<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();
$courses = $DB->get_records('course');
$courseid = "NULL";
foreach($courses as $course) {  
    if($course->fullname == "Zusatzqualifikation KI und Maschinelles Lernen") {
        $courseid = $course->id;
    }
}
print "\nCOURSEID=".$courseid."\n";
