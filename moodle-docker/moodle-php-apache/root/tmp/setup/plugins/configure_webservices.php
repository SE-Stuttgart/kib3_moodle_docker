<?php
define("CLI_SCRIPT", true);

$configfile = "/var/www/html/moodle/config.php";
require($configfile);
print "LIB DIR".$CFG->libdir.'\n';
require_once($CFG->libdir."/dmllib.php");
setup_DB();

print "SETUP DB DONE\n";
//
// Create webservice user: kib3_webservice
//
require_once($CFG->libdir."/moodlelib.php");
require_once($CFG->dirroot."/user/editlib.php");
require_once($CFG->dirroot."/user/lib.php");
$user = new \stdClass();
$user->firstname = getenv("MOODLE_WEBSERVICE_USER");
$user->lastname = getenv("MOODLE_WEBSERVICE_USER");
$user->username = "kib3_webservice";
$user->email = "kib3@kib3.de";
$user->auth = "manual";
$user->password = getenv("MOODLE_WEBSERVICE_PASSWORD");
$user->confirmed = true;
$user->lang = current_language();
$userid = user_create_user($user, true, true);
// Plugins can perform post sign up actions once data has been validated.
// core_login_post_signup_requests($user);
print "CREATED WEBSERVICE USER " . $user->username . " CREATED\n";

// create new role 
require_once($CFG->libdir."/accesslib.php");
$context = context_system::instance();
$roleid = create_role("KIB3 Webservice", "kib3webservice", "A role that can use REST web services and create tokens", "manager");
print "CREATED ROLE\n";
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
print "ADDED ROLE PERMISSIONS\n";
// asssign role
role_assign($roleid, $userid, $context->id);
print "ASSIGNED ROLE\n";

//
// create new webservice: kib3_webservice
//
require($CFG->dirroot."/webservice/lib.php");
$ws = new webservice();
$ws_id = $ws->add_external_service((object)[
    "name" => "kib3_webservices",
    "enabled" => 1,
    'requiredcapability' => '',
    'restrictedusers' => true,
    'shortname' => 'kib3_webservices',
    'downloadfiles' => false,
    'uploadfiles' => false,
]);
print "Added webservice".$ws_id."\n";
$serviceuser = new stdClass();
$serviceuser->externalserviceid = $ws_id;
$serviceuser->userid = $userid;
$ws->add_ws_authorised_user($serviceuser);
print "REGISTERED WEBSERVICE USER\n";

// add webservice functions
if(getenv("PLUGIN_ICECREAMGAME") == "true") {
    $ws->add_external_function_to_service("mod_icecreamgame_getconfig", $ws_id);
    $ws->add_external_function_to_service("mod_icecreamgame_assigngroup", $ws_id);
    $ws->add_external_function_to_service("mod_icecreamgame_addguess", $ws_id);
    $ws->add_external_function_to_service("mod_icecreamgame_sendgrade", $ws_id);
    print "Added icecreamgame webservice functions\n";
}
if(getenv("PLUGIN_SLIDEFINDER") == "true") {
    $ws->add_external_function_to_service("block_slidefinder_get_searched_locations", $ws_id);
    print "Added slidefinder webservice functions\n";
}
if(getenv("PLUGIN_CHATBOT") == "true") {
    $ws->add_external_function_to_service("block_chatbot_get_usersettings", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_set_usersettings", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_section_id", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_section_completionstate", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_branch_quizes_if_complete", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_has_seen_any_course_modules", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_last_viewed_course_modules", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_first_available_course_module", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_course_module_content_link", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_available_new_course_sections", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_icecreamgame_course_module_id", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_next_available_course_module_id", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_count_viewed_course_modules", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_user_statistics", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_last_user_weekly_summary", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_closest_badge", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_badge_info", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_h5pquiz_params", $ws_id);
    $ws->add_external_function_to_service("block_chatbot_get_oldest_worst_grade_attempts", $ws_id);
    print "Added chatbot webservice functions\n";
}

//
// enrol webservice user in the course
//
require_once($CFG->dirroot."/lib/enrollib.php");
// get course id
$course_id = intval(file_get_contents("/tmp/setup/data/restored_course_info.txt"));
// enrol
$instance = $DB->get_record('enrol', ['courseid' => $course_id, 'enrol' => 'manual']);
$enrolplugin = enrol_get_plugin($instance->enrol);
$enrolplugin->enrol_user($instance, $userid, $roleid);

//
// generate token
//
$ws->generate_user_ws_tokens($userid);
print "GENERATED TOKENS\n";