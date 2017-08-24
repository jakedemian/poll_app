<?php
    ini_set("display_errors", "1");
    error_reporting(E_ALL);
    require(__dir__ . "/../../includes/CommonIncludes.php");

    $pollId = $_GET["id"];

    $conn = getDatabaseConnection();
    if ($stmt = $conn->prepare("SELECT prompt FROM polls WHERE poll_id=? LIMIT 1;")){
        $stmt->bind_param("s", $pollId);    
        $stmt->execute();
        $stmt->bind_result($prompt);
        $stmt->fetch();
        $stmt->close();
    }

    $queries = array();
    if ($stmt = $conn->prepare("SELECT query_id, query_text FROM queries WHERE poll_id=?;")){
        $stmt->bind_param("s", $pollId);    
        $stmt->execute();
        $result = $stmt->get_result();
        while ($rowMap = $result->fetch_assoc()) {
            $queryId = $rowMap["query_id"];
            $txt = $rowMap["query_text"];
            $queries[$queryId] = $txt;            
        }
        $stmt->close();
    }

    $responses = array();
    foreach($queries as $key => $value){
        $responses[$key] = 0;
    }
    if ($stmt = $conn->prepare("SELECT pollquery_id FROM responses WHERE poll_id=?;")){
        $stmt->bind_param("s", $pollId);    
        $stmt->execute();
        $result = $stmt->get_result();
        while ($rowMap = $result->fetch_assoc()) {
            $queryId = $rowMap["pollquery_id"];
            $responses[$queryId]++;
        }
        $stmt->close();
    }

    $queriesString = "{";
    $i = 0;
    foreach($queries as $key => $value){
        $queriesString .= "'" . $key . "':'" . $value . "'";
        if($i < sizeof($queries) - 1){
            $queriesString .= ",";
        }
        $i++;
    }
    $queriesString .= "}";

    $respString = "{";
    $i = 0;
    foreach($responses as $key => $value){
        $respString .= "'" . $key . "':'" . $value . "'";
        if($i < sizeof($responses) - 1){
            $respString .= ",";
        }
        $i++;
    }
    $respString .= "}";
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
    <body ng-app="mainApp" ng-controller="ResultsController" ng-init="init('<?php safePrint($prompt); ?>',<?php safePrint($queriesString); ?>,<?php safePrint($respString); ?>)">
        <div class="mainWrapper">
            <h1>{{prompt}}</h1>
            <div class="results-row" ng-repeat="(key, value) in queries">
                <p>{{value}}</p> --> {{responses[key]}}
            </div>
        </div>
    </body>
</html>