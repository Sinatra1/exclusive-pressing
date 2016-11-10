'use strict';
exclusivepressing_site.config(['$routeProvider', function($routeProvider) {
  $routeProvider
    .when('/site/index', {
        templateUrl: 'views/site/index.html',
        controller: 'index'
    })
    .otherwise({
        redirectTo: '/site/index'
    });
}])
.controller('index', ['$scope', '$http', function($scope,$http) {
    $scope.message = 'Вы находитесь на главной странице';
}]);