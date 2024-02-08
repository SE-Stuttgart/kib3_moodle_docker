<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

// get course
$course_id = intval(file_get_contents("/tmp/setup/data/restored_course_info.txt"));
$course = $DB->get_record('course', array('id'=>$course_id));

// activate chatbot for restored course
if($DB->record_exists("config_plugins", array("plugin" => "block_chatbot", "name" => "courseids"))) {
    $chatbot_course_ids_config = $DB->get_record("config_plugins", array("plugin" => "block_chatbot", "name" => "courseids"));
    $chatbot_course_ids_config->value = strval($course->id);
    $DB->update_record("config_plugins", $chatbot_course_ids_config);
    echo "\nUpdated chatbot config: Activated for course " . $course->id; 
} else {
    $DB->insert_record("config_plugins", array(
        "plugin" => "block_chatbot",
        "name" => "courseids",
        "value" => strval($course->id)
    ));
    echo "\nInserted chatbot config: Activated for course " . $course->id; 
}

// sync moodle course module views / completions with chatbot (for restored course)
require_once($CFG->dirroot . "/blocks/chatbot/lib.php");
sync_course_module_history($course_id);

// set course page context
require_once($CFG->libdir.'/blocklib.php');
$page = new moodle_page();
$page->set_course($course);

// add block to course pages (everywhere)
if(!$DB->record_exists('block_instances', array('blockname' => "chatbot"))) {
    $page->blocks->add_region(BLOCK_POS_RIGHT, false);
    $page->blocks->add_block('chatbot', BLOCK_POS_RIGHT, 1, true, '*');
    echo "\nAdded chatbot block to course" . $course->id."\n";
} else {
    echo "\nFound existing chatbot block in course" . $course->id."\n";
}
