<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require(__dir__ . "/../../includes/CommonIncludes.php");

$answers = array();
foreach($_POST as $key => $value){
    if (strstr($key, 'idx')){
        array_push($answers, $value);
    }
}
$pollId = getPostParam("pollId");

$conn = getDatabaseConnection();
if ($stmt = $conn->prepare("SELECT multiple_select,ip_lock FROM polls WHERE poll_id=? LIMIT 1;")){
    $stmt->bind_param("s", $pollId);    
    $stmt->execute();
    $stmt->bind_result($multiSelect,$ipLock);
    $stmt->fetch();
    $stmt->close();
}

if($multiSelect === 0 && sizeof($answers) > 1){
    die("You cannot select multiple answers for this poll.");
}

if($ipLock === 1){    
    // check if this user has already answered this poll
    $userIp = $_SERVER['REMOTE_ADDR'];
    if ($stmt = $conn->prepare("SELECT ip FROM responses WHERE poll_id=? and ip=?;")){
        $stmt->bind_param("ss", $pollId, $userIp);    
        $stmt->execute();
        $stmt->bind_result($ipFromDB);
        $stmt->fetch();
        $stmt->close();
    }

    if($ipFromDB === $userIp){
        die("This user has already responded to this poll and should be redirected to the results page with a warning message");
    }
}

for($i = 0; $i < sizeof($answers); $i++){    
    // TODO combine this logic into one sql statement    
    if ($stmt = $conn->prepare("SELECT query_id FROM queries WHERE poll_id=? AND query_index=?;")){
        $stmt->bind_param("ss", $pollId, $answers[$i]);    
        $stmt->execute();
        $stmt->bind_result($queryId);
        $stmt->fetch();
        $stmt->close();

        $responseValue = 1; // FIXME why even have this?
        if ($queryId !== null && $stmt = $conn->prepare("INSERT INTO responses (poll_id,pollquery_id,responsevalue,ip) VALUES (?,?,?,?);")){
            $stmt->bind_param("siis", $pollId, $queryId, $responseValue, $userIp);    
            $stmt->execute();
            $stmt->close();
        }
    }
}
$conn->close();

header("Location: /" . $pollId);
exit;