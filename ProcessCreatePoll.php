<?php
require(__dir__ . "/includes/CommonIncludes.php");

// read in db_conn_info.xml to get connection information
$conn = getDatabaseConnection();

$prompt = getPostParam("createPrompt");
if($prompt == null){
    die("Cannot create a poll without a prompt.");
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
if ($stmt = $conn->prepare("INSERT INTO polls (poll_id, prompt, shuffle, multiple_select, ip_lock) VALUES (?,?,?,?,?);")){
    $stmt->bind_param("ssiii", $pollId, $prompt, $shuffle, $allowMultiple, $ipLock);    
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

