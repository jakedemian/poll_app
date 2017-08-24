<!-- BEGIN create.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Poll or something</title>
    <!-- TODO move these to a common includes php file -->
    <link rel="stylesheet" type="text/css" href="./assets/dist/css/main.css">
    <script src="./assets/lib/jquery-min.js"></script>
    <script src="./assets/lib/angularjs-min.js"></script>
    
    <script src="./assets/dist/js/main.min.js"></script>
</head>
<body ng-app="mainApp" ng-controller="CreatePollController" ng-init="init(2)">
    <h1>Create new poll</h1>
    <form id="createPollForm" method="post" action="./src/controllers/ProcessCreatePoll.php">
        <input class="create-prompt" id="createPrompt" name="createPrompt" type="text" placeholder="Prompt"/>
        <input class="create-input" ng-repeat="n in questions" id="q_{{$index}}" name="q_{{$index}}" type="text" placeholder="Option {{$index + 1}}" ng-keypress="createInputKeypress($event);" ng-cloak/>
        
        <a href="javascript:void(0);" class="button" ng-click="addQuestion()">Add Question</a>
        <a href="javascript:void(0);" class="button" ng-click="createSubmit()">Create this Poll</a>

        <br/>
        
        <div class="checkbox-container">
            <input type="checkbox" class="checkbox" name="enableMultipleAnswers" id="enableMultipleAnswers" />
            <label for="enableMultipleAnswers">Allow multiple selections</label> <br />
        </div>
        <div class="checkbox-container">
            <input type="checkbox" class="checkbox" name="enableShuffle" id="enableShuffle"/>
            <label for="enableShuffle">Shuffle Options</label> <br />
        </div>
        <div class="checkbox-container">
            <input type="checkbox" class="checkbox" name="disableIpRestriction" id="disableIpRestriction"/>
            <label for="disableIpRestriction">Allow multiple submissions from same IP address</label> <br/>
        </div>
    </form>
</body> 
</html>
<!-- END create.php -->
