<!-- BEGIN create.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Poll or something</title>
    <!-- TODO move these to a common includes php file -->
    <link rel="stylesheet" type="text/css" href="./assets/dist/css/main.css">
    <script src="./assets/lib/jquery-min.js"></script>
    <script src="./assets/lib/angularjs-min.js"></script>
    
    <script src="./assets/src/js/app.js"></script>
    <script src="./assets/src/js/CreatePoll.js"></script>
</head>
<body ng-app="mainApp" ng-controller="CreatePollController" ng-init="init(3)">
    <h1>Create new poll</h1>
    <form id="createPollForm" method="post" action="ProcessCreatePoll.php">
        <input class="create-input" ng-repeat="n in questions" id="q_{{$index}}" name="q_{{$index}}" type="text" ng-keypress="createInputKeypress($event);"/>
        
        <a href="javascript:void(0);" class="button" ng-click="addQuestion()">Add Question</a>
        <a href="javascript:void(0);" class="button" ng-click="createSubmit()">Create this Poll</a>

        <br/>

        <input type="checkbox" name="enableMultipleAnswers" id="enableMultipleAnswers" />
        <label for="enableMultipleAnswers">Allow multiple selections</label> <br />

        <input type="checkbox" name="enableShuffle" id="enableShuffle"/>
        <label for="enableShuffle">Shuffle Answers</label> <br />

        <input type="checkbox" name="enableIpRestriction" id="enableIpRestriction"/>
        <label for="enableIpRestriction">Allow multiple submissions from same IP address</label> <br/>
    </form>
</body>
</html>
<!-- END create.php -->
