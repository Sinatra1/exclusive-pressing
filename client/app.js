'use strict';

var serviceBase = 'http://exclusive-pressing.server/';

var exclusivepressing = angular.module('exclusivepressing', [
    'ngRoute',
    'exclusivepressing.site',
    'exclusivepressing.user',
    'ui.bootstrap'
]);
// рабочий модуль
var exclusivepressing_site = angular.module('exclusivepressing.site', ['ngRoute']);
var exclusivepressing_user = angular.module('exclusivepressing.user', ['ngRoute']);

exclusivepressing.config(['$routeProvider', function ($routeProvider) {

        $routeProvider.otherwise({redirectTo: '/site/index'});
    }]);