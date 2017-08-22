//
//
// TODO     COMPILE THIS INTO DIST DIRECTORY AND LOADED FROM THERE
//
//

/**
 * CreatePollController definition and implementation
 * 
 * Created: August 21, 2017
 * Creator: Jake Demian <jakedemian@gmail.com>
 */

app.controller('CreatePollController', function($scope) {
    // an array to store our questions
    $scope.questions = [];

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
    $scope.addQuestion = function(){
        $scope.questions.push({
            "questionText":""
        });
    };
    
    /**
     * Handle a keypress event on a form input, only perform logic if pressed key was Enter (keyCode == 13)
     */
    $scope.createInputKeypress = function(e){
        if(e.keyCode === 13){
            e.preventDefault();
            
            var idx = Number(e.target.id.replace("q_", ""));
            if(idx === this.questions.length - 1){
                // TODO add a check to make sure we aren't at the max number of inputs
                this.addQuestion();
            }

            // focus the next element after angular has updated
            runAfterRender(function () {
                var nextId = "#q_" + (++idx);
                $(nextId).focus();
            });
        }
    };

    /**
     * Submits the create poll form after some processing of the input.
     */
    $scope.createSubmit = function(){
        var form = $("#createPollForm");

        var inputs = form.find("input");

        // TODO verify that there are not too many inputs

        for(var i = 0; i < inputs.length; i++){
            // front end processing of the inputs.  if blank, get rid of it
            var inputValue = inputs[i].value;
            if(inputValue.trim() === "" || inputValue === null){
                $(inputs[i]).remove();
            }
        }

        form.submit();
    }
});
