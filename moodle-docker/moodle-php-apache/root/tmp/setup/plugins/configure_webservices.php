<?php
define("CLI_SCRIPT", true);

$configfile = "/var/www/html/config.php";
require($configfile);
print "LIB DIR".$CFG->libdir;
require_once($CFG->libdir."/dmllib.php");
setup_DB();


//
// Create webservice user: kib3_webservice
//
require_once($CFG->libdir."/moodlelib.php");
require_once($CFG->libdir."/authlib.php");
require_once($CFG->dirroot."/login/lib.php");
require_once($CFG->dirroot."/user/editlib.php");
$user = new \stdClass();
$user->firstname = getenv("MOODLE_WEBSERVICE_USER");
$user->lastname = getenv("MOODLE_WEBSERVICE_USER");
$user->username = "kib3_webservice";
$user->password = getenv("MOODLE_WEBSERVICE_PASSWORD");
$user->confirmed = 1;
$user->lang = current_language();
$auth_plugin = get_auth_plugin("email");
$user = signup_setup_new_user($user);
// Plugins can perform post sign up actions once data has been validated.
core_login_post_signup_requests($user);
$auth_plugin->user_signup($user, true);

// create new role 
require_once($CFG->libdir."/accesslib.php");
$context = context_system::instance();
$roleid = create_role("KIB3 Webservice", "kib3webservice", "A role that can use REST web services and create tokens", "manager");
// asssign rights: webservice/rest:use
assign_capability("webservice/rest:use", CAP_ALLOW, $roleid, $context->id, true);
// assign rights: moodle/webservice:createtoken 
assign_capability("moodle/webservice:createtoken", CAP_ALLOW, $roleid, $context->id, true);

// update role permissions for all authenticated users
$roleid_authenticated_user = $DB->get_records('role', array("archetype"=>"user"));
foreach($roleid_authenticated_user as $role_user) {
    // asssign rights: webservice/rest:use
    assign_capability("webservice/rest:use", CAP_ALLOW, $role_user->id, $context->id, true);
    // assign rights: moodle/webservice:createtoken 
    assign_capability("moodle/webservice:createtoken", CAP_ALLOW, $role_user->id, $context->id, true);
}

// asssign role
$userid = $DB->get_record('user', array('username'=>'kib3_webservice'))->id;
role_assign($roleid, $userid, $context->id);


// create new webservice: kib3_webservice
require($CFG->dirroot."/webservice/lib.php");
$webservice = new webservice();
$ws = new \stdClass();
$ws->name = "kib3_webservices";
$ws->shortname = "moodleservice";
$ws->enabled = true;
$ws->restrictedusers = false;
$ws_id = $webservice->add_external_service($ws);
print "Added webservice".$ws_id."\n";

// add webservice functions
if(getenv("PLUGIN_ICECREAMGAME") == true) {
    $webservice->add_external_function_to_service("mod_icecreamgame_getconfig", $ws_id);
    $webservice->add_external_function_to_service("mod_icecreamgame_assigngroup", $ws_id);
    $webservice->add_external_function_to_service("mod_icecreamgame_addguess", $ws_id);
    $webservice->add_external_function_to_service("mod_icecreamgame_sendgrade", $ws_id);
    print "Added icecreamgame webservice functions\n";
}
if(getenv("PLUGIN_SLIDEFINDER") == true) {
    $webservice->add_external_function_to_service("block_slidefinder_get_searched_locations", $ws_id);
    print "Added slidefinder webservice functions\n";
}