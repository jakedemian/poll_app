/**
 * AnswerPollController definition and implementation
 * 
 * Created: August 23, 2017
 * Creator: Jake Demian <jakedemian@gmail.com>
 */

app.controller("AnswerPollController", ["$scope", function($scope) {
    $scope.prompt = null;
    $scope.queries = [];
    $scope.shuffle = false;
    $scope.ipLock = true;
    $scope.multiSelect = false;

    $scope.shuffleArray = function(array) {
        var newArray = [];
           
        while(array.length > 0){
            var randomIdx = Math.floor(Math.random() * array.length);
            newArray.push(array[randomIdx]);
            array.splice(randomIdx, 1);
        }

        return newArray;
    };

    $scope.init = function(_prompt, _queries, _shuffle, _ipLock, _multiSelect){
        $scope.prompt = _prompt;
        $scope.queries = _queries;
        $scope.shuffle = _shuffle;
        $scope.ipLock = _ipLock;
        $scope.multiSelect = _multiSelect;

        if($scope.shuffle){
            $scope.queries = $scope.shuffleArray($scope.queries);
        }
    };

    $scope.select = function(e){
        var thisSelection = $(e.currentTarget);

        if(!$scope.multiSelect){
            $(".selection-box").removeClass("selected");
        }

        thisSelection.toggleClass("selected");
    };

    $scope.submitPoll = function(){
        var form = $("#answerPollForm");
        var selectedResponses = $(".selected");
        var canSubmit = false;

        for(var i = 0; i < selectedResponses.length; i++){
            var queryIndex = $(selectedResponses[i]).attr("data-index");
            form.append("<input type='hidden' name='idx" + i + "' value='" + queryIndex + "' />");
            canSubmit = true;
        }

        if(canSubmit){
            form.submit();
        }
    };
}]);