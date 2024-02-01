<?php
define('CLI_SCRIPT', true);

// workaround: somehow, when we execute this script, the memory limit
ini_set('memory_limit', getenv("MOODLE_SERVER_MEMORY_LIMIT"));

$backup_file = "/tmp/setup/data/" . getenv("COURSE_BACKUP_FILE");
echo "\nReading course name from backup file: $backup_file";

// Extract the tar file contents to a temporary directory
try {
    // Extract XML file describing the course backup
    $tar = new PharData($backup_file);
    $tar->extractTo("./data", "./moodle_backup.xml", true);
    
    // Parse the XML file describing the course backup
    $xmlString = file_get_contents('./data/moodle_backup.xml');
    $xml = new SimpleXMLElement($xmlString);

    // Find the course fullname inside the XML
    $course_fullname = (string)($xml->xpath("/moodle_backup/information/original_course_fullname"))[0];
    echo "\n$course_fullname\n";

    // Use the DB and the course fullname to get the ID of the restored course
    $configfile = '/var/www/html/moodle/config.php';
    require($configfile);
    require_once($CFG->libdir.'/dmllib.php');
    setup_DB();

    $_likesql_coursename = $DB->sql_like('fullname', ':coursename');
    $course = $DB->get_record_sql("SELECT * FROM {course} WHERE $_likesql_coursename",
                                array(
                                    "coursename" => $course_fullname
                                ));
    echo "\nRESTORED COURSE ID: " . $course->id;
    file_put_contents("/tmp/setup/data/restored_course_info.txt", strval($course->id));

}
catch (Exception $e) {
    // handle errors
    echo $e;
}
