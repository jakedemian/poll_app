<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require(__dir__ . "/includes/CommonIncludes.php");

$path = ltrim($_SERVER['REQUEST_URI'], '/');    // Trim leading slash(es)
$elements = explode('/', $path);                // Split path on slashes

$phpFile = "";
if(sizeof($elements) < 2){
    if($elements[0] == null){
        $phpFile = __dir__ . "/src/views/create.php";
    } else if(strlen($elements[0]) == Constants::$POLL_ID_SIZE){
        // make sure this key exists in the database
        $pollId = $elements[0];     

        // read in db_conn_info.xml to get connection information
        $conn = getDatabaseConnection();
        if ($stmt = $conn->prepare("SELECT poll_id FROM polls WHERE poll_id=?")) {
            $stmt->bind_param("s", $pollId);
            $stmt->execute();
            $stmt->bind_result($res);
            $stmt->fetch();

            if($res === $pollId){
                $_GET["id"] = $pollId;
                $phpFile = __dir__ . "/src/controllers/ProcessViewPoll.php";
            } else {
                $phpFile = __dir__ . "/src/views/404.php";
            }

            $stmt->close();
        }        
        $conn->close();
    } else {
        $phpFile = __dir__ . "/src/views/404.php";
    }
} else {
    $phpFile = __dir__ . "/src/views/404.php";
}

require($phpFile);