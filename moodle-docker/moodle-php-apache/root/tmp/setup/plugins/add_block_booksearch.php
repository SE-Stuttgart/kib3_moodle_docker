<?php
define('CLI_SCRIPT', true);

$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();


// list of existing backup files
$backup_files = [];
$path_zq = "/tmp/setup/data/restored_course_info_zq.txt";
$path_dqr5 = "/tmp/setup/data/restored_course_info_dqr5.txt";
if (file_exists($path_zq)) {
    $backup_files["zq"] = $path_zq;
}
if (file_exists($path_dqr5)) {
    $backup_files["dqr5"] = $path_dqr5;
}
foreach($backup_files as $backup_file) {
    // get course id
    $course_id = intval(file_get_contents($backup_file));
    echo "\nAdding booksearch block to course".$course_id."\n";
    $course = $DB->get_record('course', array('id'=>$course_id));

    // set course page context
    require_once($CFG->libdir.'/blocklib.php');
    $page = new moodle_page();
    $page->set_course($course);

    // add block to course pages
    $page->blocks->add_region(BLOCK_POS_RIGHT, false);
    $page->blocks->add_block('booksearch', BLOCK_POS_RIGHT, 1, false, "course-*"); // show on course pages (excludes activity sub-pages)
    print "Added booksearch block to course".$course->id."\n";
}