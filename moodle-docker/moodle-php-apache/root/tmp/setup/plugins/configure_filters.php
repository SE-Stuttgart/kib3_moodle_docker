<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// filter activitynames: change sortorder
$filter_activitynames = $DB->get_record('filter_active', array('filter'=>'activitynames'));
$filter_activitynames_sortoder = $filter_activitynames->sortorder;

// filter sectionnames: activate
$filter_sectionnames = new stdClass();
$filter_sectionnames->filter = "sectionnames";
$filter_sectionnames->contextid = "1";
$filter_sectionnames->active = "1";

# make sure sectionnames comes before actvitynames w.r.t. sortorder
$filter_sectionnames->sortorder = 1;
if($filter_activitynames_sortoder >= $filter_sectionnames->sortorder) {
    $filter_activitynames->sortorder = $filter_sectionnames->sortorder + 1;
    $DB->update_record('filter_active', $filter_activitynames);
}
$filter_sectionnames_id = $DB->insert_record('filter_active', $filter_sectionnames);

print "\nCONFIGURED FILTER SORTORDER: sectionnames -> ".$filter_sectionnames->sortorder.", FILTER activitynames -> ".$filter_activitynames->sortorder."\n";
