<?php
require(__dir__ . "/includes/Constants.php");
require(__dir__ . "/includes/Utilities.php");

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

$allowMultiple = getPostParam("enableMultipleAnswers") == "" ? false : true;
$shuffle = getPostParam("enableShuffle") == "" ? false : true;
$ipLock = getPostParam("enableIpRestriction") == "" ? false : true;

$queries = array();
foreach($_POST as $key => $value){
    if(strpos($key, "q_") !== false && $value !== ""){
        array_push($queries, $value);
    }
}

if(sizeof($queries) < 2){
    die("There are not enough queries to make a poll.  A minimum of two queries are required.");
}

$pollId = generateRandomString(Constants::$POLL_ID_SIZE);
// TODO make sure it doesn't already exist (even though it's virtually impossible that it does)
if ($stmt = $conn->prepare("INSERT INTO polls (poll_id, shuffle, multiple_select, ip_lock) VALUES (?,?,?,?);")){
    $stmt->bind_param("siii", $pollId, $shuffle, $allowMultiple, $ipLock);    
    $stmt->execute();

    // TODO verify?

    $stmt->close();
}

for($i = 0; $i < sizeof($queries); $i++){
    if($stmt = $conn->prepare("INSERT INTO queries (poll_id, query_index, query_text) VALUES (?,?,?);")){
        $thisVal = $queries[$i];
        $stmt->bind_param("sis", $pollId, $i, $thisVal);
        $stmt->execute();
        // TODO verify?    
        $stmt->close();        
    } else {
        die("A fatal error occurred while creating the poll.  Please try again later.");
        break;
    }
}

$conn->close();

header("Location: /" . $pollId);

