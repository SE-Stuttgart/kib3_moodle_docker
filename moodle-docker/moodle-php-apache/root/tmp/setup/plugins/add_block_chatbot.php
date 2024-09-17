<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();
require_once($CFG->libdir.'/blocklib.php');
require_once($CFG->dirroot . "/blocks/chatbot/lib.php");

// list of existing backup files
$backup_files = [];
$path_zq = "/tmp/setup/data/restored_course_info_zq.txt";
// TODO add support for the DQR5 here once chatbot is ready
// $path_dqr5 = "/tmp/setup/data/restored_course_info_dqr5.txt";
if (file_exists($path_zq)) {
    $backup_files["zq"] = $path_zq;
}
// if (file_exists($path_dqr5)) {
//     $backup_files["dqr5"] = $path_dqr5;
// }
foreach($backup_files as $backup_file) {
    // get course
    $course_id = intval(file_get_contents($backup_file));
    $course = $DB->get_record('course', array('id'=>$course_id));

    // activate chatbot for restored course
    $chatbot_course_ids_config = $DB->get_record("config_plugins", array("plugin" => "block_chatbot", "name" => "courseids"));
    set_config("courseids", strval($course->id), "block_chatbot");
    echo "\nUpdated chatbot config: Activated for courses " . get_config("block_chatbot", "courseids"); 

    // sync moodle course module views / completions with chatbot (for restored course)
    sync_course_module_history($course_id);

    // set course page context
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

}   
