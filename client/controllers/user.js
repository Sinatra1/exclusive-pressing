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
                .when('/user/view/:userId', {
                    templateUrl: 'views/user/view.html',
                    controller: 'view',
                    resolve: {
                        user: function (services, $route) {
                            var userId = $route.current.params.userId;
                            return services.getUser(userId);
                        },
                        entries: function (entryService, $route) {
                            var userId = $route.current.params.userId;
                            return entryService.getUserEntries(userId);
                        },
                    }
                })
                .when('/user/auth', {
                    templateUrl: 'views/user/auth.html',
                    controller: 'auth',
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

exclusivepressing_user.controller('index', ['$scope', '$http', '$location', 'services', 'authService',
    function ($scope, $http, $location, services, authService) {
        $scope.authService = authService;
        var url = $location.url();

        if (authService.isAuth()) {
            services.getUsers().then(function (users) {
                $scope.users = users.data;
            });
        } else if (!authService.isPublicUrl()) {
            $location.path('/site/index');
        }

        $scope.deleteUser = function (userID) {
            if (confirm("Are you sure to delete user number: " + userID) == true && userID > 0) {
                services.deleteUser(userID);
            }
        };
    }]).controller('create', ['$scope', '$http', 'services', '$location', 'dateServices', 'user',
    function ($scope, $http, services, $location, dateServices, user) {

        $scope.dateServices = dateServices;
        $scope.services = services;

        $scope.createUser = function (user) {
            var results = services.createUser(user, $scope);
        }
    }]).controller('update', ['$scope', '$http', '$routeParams', 'services', '$location', 'user',
    function ($scope, $http, $routeParams, services, $location, user) {

        var original = user.data;
        $scope.user = angular.copy(original);

        $scope.user = services.prepareOptions($scope.user);

        $scope.isClean = function () {
            return angular.equals(original, $scope.user);
        }

        $scope.updateUser = function (user) {
            var results = services.updateUser(user);
        }

        $scope.addOptionRow = function () {
            $scope.user.options[$scope.user.options.length] = '';
        }

        $scope.deleteOptionRow = function (rowIndex) {
            $scope.user.options.splice(rowIndex, 1);
        }

        $scope.isFilledAllOptions = function () {
            if (!$scope.user.options) {
                return false;
            }

            for (var i = 0; i < $scope.user.options.length; i++) {
                if (!$scope.user.options[i]) {
                    return false;
                }
            }

            return true;
        }

    }]).controller('view', ['$scope', '$http', '$routeParams', 'entryService', '$location', 'user', 'entries', 'services',
    function ($scope, $http, $routeParams, entryService, $location, user, entries, services) {
        var original = user.data;
        $scope.user = angular.copy(original);
        
        $scope.user = services.prepareOptions($scope.user);

        $scope.entries = entries.data;
    }]).controller('auth', ['$scope', '$http', 'services', '$location',
    function ($scope, $http, services, $location) {

        $scope.authUser = function (user) {
            var results = services.authUser(user, $scope);
        }
    }]);