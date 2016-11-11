'use strict';
exclusivepressing_user.config(['$routeProvider', function ($routeProvider) {
        $routeProvider
                .when('/user/index', {
                    templateUrl: 'views/user/index.html',
                    controller: 'index'
                })
                .when('/user/create', {
                    templateUrl: 'views/user/create.html',
                    controller: 'create',
                    resolve: {
                        user: function (services, $route) {
                            return [];
                        },
                    }
                })
                .when('/user/auth', {
                    templateUrl: 'views/user/auth.html',
                    controller: 'auth',
                    resolve: {
                        user: function (services, $route) {
                            return [];
                        }
                    }
                })
                .when('/user/update/:userId', {
                    templateUrl: 'views/user/update.html',
                    controller: 'update',
                    resolve: {
                        user: function (services, $route) {
                            var userId = $route.current.params.userId;
                            return services.getUser(userId);
                        }
                    }
                })
                .when('/user/delete/:userId', {
                    templateUrl: 'views/user/index.html',
                    controller: 'delete',
                })
                .otherwise({
                    redirectTo: '/user/index'
                });
    }]);

exclusivepressing_user.controller('index', ['$scope', '$http', 'services', 'authService',
    function ($scope, $http, services, authService) {
        $scope.authService = authService;

        if (authService.isAuth()) {
            services.getUsers().then(function (users) {
                $scope.users = users.data;
            });
        }

        $scope.deleteUser = function (userID) {
            if (confirm("Are you sure to delete user number: " + userID) == true && userID > 0) {
                services.deleteUser(userID);
                $route.reload();
            }
        };
    }]).controller('create', ['$scope', '$http', 'services', '$location', 'dateServices', 'user',
    function ($scope, $http, services, $location, dateServices, user) {

        $scope.dateServices = dateServices;
        $scope.services = services;

        $scope.createUser = function (user) {
            var results = services.createUser(user);
        }
    }]).controller('update', ['$scope', '$http', '$routeParams', 'services', '$location', 'user',
    function ($scope, $http, $routeParams, services, $location, user) {

        var original = user.data;
        $scope.user = angular.copy(original);
        $scope.isClean = function () {
            return angular.equals(original, $scope.user);
        }
        $scope.updateUser = function (user) {
            var results = services.updateUser(user);
        }
    }]).controller('auth', ['$scope', '$http', 'services', '$location', 'user',
    function ($scope, $http, services, $location, user) {

        $scope.authUser = function (user) {
            var results = services.authUser(user);
        }
    }]);