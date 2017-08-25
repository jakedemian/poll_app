/**
 * CreatePollController definition and implementation
 * 
 * Created: August 21, 2017
 * Creator: Jake Demian <jakedemian@gmail.com>
 */

app.controller("CreatePollController", ["$scope", function($scope) {
    // an array to store our questions
    $scope.questions = [];
    $scope.maxQuestions = 10;

    /**
     * executes a callback function after angular has finished re-rendering the DOM
     */
    function runAfterRender (callback) {
        setTimeout(function () {
            if (angular.isFunction(callback)) {
                callback();
            }
        }, 0);
    }

    /**
     * Initializes the create poll form with inputs
     * 
     * @param numOfInputs The number of inputs to begin with
     */
    $scope.init = function(numOfInputs){
        for(var i = 0; i < numOfInputs; i++){
            $scope.questions.push({
                "questionText":""
            });
        }
    };
    
    /**
     * Add an input to the create poll form
     */
    $scope.addQuestions = function(numOfQuestions){
        if($scope.questions.length >= $scope.maxQuestions){
            return;
        }
        
        for(var i = 0; i < numOfQuestions; i++){
            if($scope.questions.length < $scope.maxQuestions){
                $scope.questions.push({
                    "questionText":""
                });
            } else {
                break;
            }
        }
    };
    
    /**
     * Handle a keypress event on a form input, only perform logic if pressed key was Enter (keyCode == 13)
     */
    $scope.createInputKeypress = function(e){
        if(e.keyCode === 13 || e.keyCode === 9){
            e.preventDefault();
            
            var idx = Number(e.target.id.replace("q_", ""));
            if(idx === this.questions.length - 2){
                // TODO add a check to make sure we aren't at the max number of inputs
                this.addQuestions(1);
            } else if(idx == this.questions.length - 1){
                this.addQuestions(2);
            }

            // focus the next element after angular has updated
            runAfterRender(function () {
                var nextId = "#q_" + (++idx);
                $(nextId).focus();
            });
        }
    };

    $scope.check = function(e){
        var thisSelection = $(e.currentTarget);
        thisSelection.toggleClass("checked");
    };

    /**
     * Submits the create poll form after some processing of the input.
     */
    $scope.createSubmit = function(){
        var form = $("#createPollForm");
        var checkBoxes = $(".checkbox");        
        var inputs = form.find("input");

        for(var i = 0; i < checkBoxes.length; i++){
            var checkBox = checkBoxes[i];
            var name = $(checkBox).attr("data-name");
            var isChecked = $(checkBox).hasClass("checked");
            form.append("<input type='hidden' name='" + name + "' value='" + isChecked + "' />");
        }

        for(i = 0; i < inputs.length; i++){
            // front end processing of the inputs.  if blank, get rid of it
            var inputValue = inputs[i].value;
            if(inputValue.trim() === "" || inputValue === null){
                $(inputs[i]).remove();
            }
        }

        form.submit();
    };
}]);

$(document).ready(function(){
    $("#createPrompt").focus();
});