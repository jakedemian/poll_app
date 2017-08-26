<!-- BEGIN create.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Poll or something</title>
    <!-- TODO move these to a common includes php file -->
    <link rel="stylesheet" type="text/css" href="./assets/dist/css/main.css">
    <link rel="stylesheet" type="text/css" href="./assets/lib/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="./assets/lib/jquery-min.js"></script>
    <script src="./assets/lib/angularjs-min.js"></script>
    
    <script src="./assets/dist/js/main.min.js"></script>
</head>
<body ng-app="mainApp" ng-controller="CreatePollController" ng-init="init(2)">
    <div class="mainWrapper">
        <div class="title-container">
            <h1>Dewpoll</h1>
        </div>
        <form class="create-poll-form" id="createPollForm" method="post" action="./src/controllers/ProcessCreatePoll.php">
            <input class="create-prompt" autocomplete="off" id="createPrompt" name="createPrompt" type="text" placeholder="Enter a prompt for this poll"/>
            <input class="create-input" autocomplete="off" ng-repeat="n in questions" id="q_{{$index}}" name="q_{{$index}}" type="text" placeholder="Option {{$index + 1}}" ng-keydown="createInputKeypress($event);" ng-cloak/>
            
            <div class="create-button">
                <a href="javascript:void(0);" class="create-button-link" ng-click="createSubmit()">Create this Poll</a>
            </div>
            
            <div class="checkbox" data-name="enableMultipleAnswers" ng-click="check($event)">
                <div class="check-container">
                    <i class="fa fa-check fa-lg"></i>
                </div>
                <span>Allow multiple selections</span>
            </div>
            <div class="checkbox" data-name="enableShuffle" ng-click="check($event)">
                <div class="check-container">
                    <i class="fa fa-check fa-lg"></i>
                </div>
                <span>Shuffle options</span>
            </div>
            <div class="checkbox" data-name="disableIpRestriction" ng-click="check($event)">
                <div class="check-container">
                    <i class="fa fa-check fa-lg"></i>
                </div>
                <span>Allow multiple submissions from same IP address</span>
            </div>
        </form>

        

    </div>
</body> 
</html>
<!-- END create.php -->
