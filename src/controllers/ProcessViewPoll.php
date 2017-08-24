<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require(__dir__ . "/../../includes/CommonIncludes.php");

// other validation?
if(!isset($_GET["id"])){
    header("Location: /");
    exit;
}
$pollId = $_GET["id"];
$conn = getDatabaseConnection();
if ($stmt = $conn->prepare("SELECT ip_lock FROM polls WHERE poll_id=?;")){
    $stmt->bind_param("s", $pollId);    
    $stmt->execute();
    $stmt->bind_result($ipLock);
    $stmt->fetch();
    $stmt->close();
}

$phpFile = "";
if(!isset($_COOKIE[$pollId])) {    
    if($ipLock === 1){ // if user is locked out and can only view results OR if they clicked "View Results"
        $ip = $_SERVER['REMOTE_ADDR'];

        if ($stmt = $conn->prepare("SELECT ip FROM responses WHERE poll_id=? AND ip=? LIMIT 1;")){
            $stmt->bind_param("ss", $pollId, $ip);    
            $stmt->execute();
            $stmt->bind_result($ipRes);
            $stmt->fetch();
            $stmt->close();
        }

        if($ipRes != null){
            // iplock is on and you're already taken this poll, so go to results
            $phpFile = __dir__ . "/../views/results.php";
        } else {
            $phpFile = __dir__ . "/../views/answer.php"; // ip locked, but you havent responded yet
        }
    } else if($ipLock === 0){ // otherwise, show the user the poll questions
        $phpFile = __dir__ . "/../views/answer.php";
    } else {
        die("whoops");
    }
} else {
    $phpFile = __dir__ . "/../views/results.php"; // this user has already answered this poll
}

require($phpFile);
