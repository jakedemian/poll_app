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

    <script>
        var prompt = "<?php safePrint($prompt); ?>";
        var queries = [
            <?php for ($i = 0; $i < sizeof($queries); $i++) : ?>
                "<?php safePrint($queries[$i]); ?>"<?php if($i < sizeof($queries) - 1){safePrint(",");} ?>
            <?php endfor; ?>
        ];
        var shuffle = <?php $shuffle === 1 ? safePrint("true") : safePrint("false"); ?>;
        var ipLock = <?php $ipLock === 1 ? safePrint("true") : safePrint("false"); ?>;
        var multiSelect = <?php $multiSelect === 1 ? safePrint("true") : safePrint("false"); ?>;
    </script>
</head>
<body>
<!-- TODO -->
</body>
</html>
