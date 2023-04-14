<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// filter activitynames: change sortorder
$filter_activitynames = $DB->get_record('filter_active', array('filter'=>'activitynames'));
$filter_activitynames_sortoder = $filter_activitynames->sortorder;

// filter sectionnames: activate
$filter_sectionnames = $DB->get_record('filter_active', array('filter'=>'sectionnames'));
$filter_sectionnames->active=true;
$filter_sectionnames_sortorder = $filter_sectionnames->sortorder;
if($filter_activitynames_sortoder > $filter_sectionnames_sortorder) {
    # make sure sectionnames comes before actvitynames
    $filter_activitynames->sortorder = $filter_sectionnames_sortorder;
    $filter_sectionnames->sortorder = $filter_activitynames_sortoder;
    $DB->update_record('filter_active', $filter_activitynames);
}
$DB->update_record('filter_active', $filter_sectionnames);

print "\nCONFIGURED FILTER SORTORDER: sectionnames -> ".$filter_sectionnames->sortorder.", FILTER activitynames -> ".$filter_activitynames->sortorder."\n";
