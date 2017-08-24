app.controller("ResultsController", ["$scope", function($scope) {
    $scope.prompt = null;
    $scope.queries = {};
    $scope.responses = {};

    $scope.init = function(_prompt, _queries, _responses){
        $scope.prompt = _prompt;
        $scope.queries = _queries;
        $scope.responses = _responses;
    };
}]);