<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

# TODO create webservice user: kib3_webservice
# TODO - asssign rights: moodle/webservice:createtoken 
# TODO - asssign rights: webservice/rest:use

# create new webservice: kib3_webservice
$ws = array('name'=>'kib3_webservice',
            'enabled'=>true,
            'shortname'=>'kib3_webservice'
        );
$ws_id = $DB->insert_record('external_services', $ws);
print "Added webservice";