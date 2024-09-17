<?php
define('CLI_SCRIPT', true);

// workaround: somehow, when we execute this script, the memory limit
echo "\n=== Setting memory limit to " . getenv("MOODLE_SERVER_MEMORY_LIMIT") . " ===";
ini_set('memory_limit', getenv("MOODLE_SERVER_MEMORY_LIMIT"));

// create a list of existing backup files
$backup_files = [];
$path_zq = "/tmp/setup/data/" . getenv("COURSE_BACKUP_FILE_ZQ");
$path_dqr5 = "/tmp/setup/data/" . getenv("COURSE_BACKUP_FILE_DQR5");
if (file_exists($path_zq)) {
    $backup_files["zq"] = $path_zq;
}
if (file_exists($path_dqr5)) {
    $backup_files["dqr5"] = $path_dqr5;
}

// Use the DB and the course fullname to get the ID of the restored course
$configfile = '/var/www/html/moodle/config.php';
require($configfile);
require_once($CFG->libdir.'/dmllib.php');
setup_DB();

foreach ($backup_files as $backup_file) {
    echo "\n=== Reading course name from backup file: $backup_file ===";

    // Extract the tar file contents to a temporary directory
    try {
        // Extract XML file describing the course backup
        $tar = new PharData($backup_file);
        try {
            echo "\nTrying to extract moodle_backup.xml from top level of mbz file";
            $tar->extractTo("./data", "./moodle_backup.xml", true);
        } catch (Exception $e) {
            // handle errors
            try {
                echo "\nTrying to extract moodle_backup.xml from root of mbz file";
                $tar->extractTo("./data", "moodle_backup.xml", true);
            } catch (Exception $e) {
                // handle errors
                echo $e;
            }
        }
        
        // Parse the XML file describing the course backup
        $xmlString = file_get_contents('./data/moodle_backup.xml');
        $xml = new SimpleXMLElement($xmlString);

        // Find the course fullname inside the XML
        $course_fullname = (string)($xml->xpath("/moodle_backup/information/original_course_fullname"))[0];
        echo "\nCourse Name: $course_fullname\n";
        $_likesql_coursename = $DB->sql_like('fullname', ':coursename');
        $course = $DB->get_record_sql("SELECT * FROM {course} WHERE $_likesql_coursename",
                                    array(
                                        "coursename" => $course_fullname
                                    ));
        echo "\nRESTORED COURSE ID: " . $course->id;

        $course_type = array_search($backup_file, $backup_files); ## DQR 5 or ZQ
        echo "\n" . $course_type . "\n";
        $filename = "/tmp/setup/data/restored_course_info_" . $course_type . ".txt";
        echo "\nWriting to: " . $filename . "\n";
        file_put_contents($filename, strval($course->id));
    }
    catch (Exception $e) {
        // handle errors
        echo $e;
    }
    echo "=== Done getting restored course id ===";
}
