<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// disable the user tours (they currently introduce a bug in combination with the tiles format)
$counter = 0;
$tours = $DB->get_records('tool_usertours_tours');
foreach($tours as $tour) {
    $tour->enabled = 0;
    $DB->update_record('tool_usertours_tours', $tour);
    $counter = $counter + 1;
}

print "\nDISABLED " . $counter . " USER TOURS";