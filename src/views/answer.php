<?php
error_reporting( E_ALL );
require(__dir__ . "/../../includes/CommonIncludes.php");

function isZeroOrOne($a){
    return $a === 1 || $a === 0;
}

$conn = getDatabaseConnection();
if ($stmt = $conn->prepare("SELECT prompt,ip_lock,shuffle,multiple_select FROM polls WHERE poll_id=?;")){
    $stmt->bind_param("s", $pollId);    
    $stmt->execute();
    $stmt->bind_result($prompt,$ipLock,$shuffle,$multiSelect);
    $stmt->fetch();
    $stmt->close();
}


if($prompt == null || !isZeroOrOne($ipLock) || !isZeroOrOne($shuffle) || !isZeroOrOne($multiSelect)){
    die("There was a problem fetching poll options.");
}

$queries = array();
if ($stmt = $conn->prepare("SELECT query_text FROM queries WHERE poll_id=? ORDER BY query_index ASC;")){
    $stmt->bind_param("s", $pollId);    
    $stmt->execute();

    $result = $stmt->get_result();
    while ($rowMap = $result->fetch_assoc()) {
        $txt = $rowMap["query_text"];
        array_push($queries, $txt);
    }
}

if(sizeOf($queries) < 2){ // need at least 2 queries to have a poll
    die("There were no queries loaded.");
}

$queriesString = "[";
for($i = 0; $i < sizeof($queries); $i++){
    $queriesString .= "'" . $queries[$i] . "'";
    if($i < sizeof($queries) - 1){
        $queriesString .= ",";
    }
}
$queriesString .= "]";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Poll or something</title>
    <!-- TODO move these to a common includes php file -->
    <link rel="stylesheet" type="text/css" href="./assets/dist/css/main.css">
    <script src="./assets/lib/jquery-min.js"></script>
    <script src="./assets/lib/angularjs-min.js"></script>
    
    <script src="./assets/dist/js/main.js"></script>
</head>
<body ng-app="mainApp" ng-controller="AnswerPollController" ng-init="init('<?php safePrint($prompt); ?>', <?php safePrint($queriesString); ?>, <?php $shuffle === 1 ? safePrint("true") : safePrint("false"); ?>, <?php $ipLock === 1 ? safePrint("true") : safePrint("false"); ?>, <?php $multiSelect === 1 ? safePrint("true") : safePrint("false"); ?>)">
    <div class="answerPollPage" >
        <div class="prompt-container">
            <h1>{{prompt}}</h1>
        </div>
        <form class="answerPollForm" id="answerPollForm" method="post" action="./src/controllers/ProcessPollResponse.php">
            <input type="hidden" name="pollId" value="<?php safePrint($pollId); ?>" />
            <div ng-repeat="n in queries" data-index="{{$index}}" class="selection-box" ng-click="select($event)">
                <span class="query-text">{{n}}</span>
                <span class="icon">x</span>
            </div>
            <div class="submit-poll-button">
                <a href="javascript:void(0)" ng-click="submitPoll()" >Submit</a>
            </div>
        </form>
    </div>
</body>
</html>
