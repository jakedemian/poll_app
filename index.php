<?php
require(__dir__ . "/includes/Constants.php");
require(__dir__ . "/includes/Utilities.php");   

$path = ltrim($_SERVER['REQUEST_URI'], '/');    // Trim leading slash(es)
$elements = explode('/', $path);                // Split path on slashes

$phpFile = "";
if(sizeof($elements) < 2){
    if($elements[0] == null){
        $phpFile = "create.php";
    } else if(strlen($elements[0]) == Constants::$POLL_ID_SIZE){
        // make sure this key exists in the database
        $pollId = $elements[0];     

        // read in db_conn_info.xml to get connection information
        $connInfo=simplexml_load_file("db_conn_info.xml") or die("Error: Unable to locate db_conn_info.xml");
        $hostname = $connInfo->hostname;
        $username = $connInfo->username;
        $password = $connInfo->password;
        $defaultdb = $connInfo->defaultdb;

        if(!$hostname || !$username || !$password || !$defaultdb){
            die("A required field is missing from db_conn_info.xml");
        }

        $conn = new mysqli($hostname, $username, $password, $defaultdb);
        if ($conn->connect_errno) {
            die("Failed to connect to the database with the provided credentials.");
        }

        if ($stmt = $conn->prepare("SELECT poll_id FROM polls WHERE poll_id=?")) {
            $stmt->bind_param("s", $pollId);
            $stmt->execute();
            $stmt->bind_result($res);
            $stmt->fetch();

            if($res === $pollId){
                $_GET["id"] = $pollId;
                $phpFile = "viewpoll.php";
            } else {
                $phpFile = "404.php";
            }

            $stmt->close();
        }        
        $conn->close();
    } else {
        $phpFile = "404.php";
    }
} else {
    $phpFile = "404.php";
}

require($phpFile);