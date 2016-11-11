'use strict';
exclusivepressing_user.factory("services", ['$http', '$location', '$route', 'authService', '$cookies',
    function ($http, $location, $route, authService, $cookies) {
        var obj = {};

        obj.getUsers = function () {
            return $http.get(authService.getApiRoute('users'));
        };

        obj.createUser = function (user) {
            return $http.post(serviceBase + 'users', user)
                    .then(successHandler)
                    .catch(errorHandler);
            function successHandler(result) {

                if (result && result.status === 201 && result.data.accessToken) {
                    authService.setAuthData(result.data);
                }

                $location.path('/user/index');
            }
            function errorHandler(result) {
                alert("Error data")
                $location.path('/user/create')
            }
        };

        obj.getUser = function (userID) {
            return $http.get(authService.getApiRoute('users/' + userID));
        };

        obj.updateUser = function (user) {
            return $http.put(authService.getApiRoute('users/' + user.id), user)
                    .then(successHandler)
                    .catch(errorHandler);
            function successHandler(result) {
                $location.path('/user/index');
            }
            function errorHandler(result) {
                alert("Error data")
                $location.path('/user/update/' + user.id)
            }
        };

        obj.deleteUser = function (userID) {
            return $http.delete(authService.getApiRoute('users/' + userID))
                    .then(successHandler)
                    .catch(errorHandler);
            function successHandler(result) {
                $route.reload();
            }
            function errorHandler(result) {
                alert("Error data")
                $route.reload();
            }
        };

        obj.authUser = function (user) {
            return $http.post(serviceBase + 'auths', user)
                    .then(successHandler)
                    .catch(errorHandler);
            function successHandler(result) {

                if (result && result.status === 200 && result.data.accessToken) {
                    authService.setAuthData(result.data);
                }

                $location.path('/user/index');
            }
            function errorHandler(result) {

                alert("Error data")
                $location.path('/user/auth')
            }
        };

        obj.isEaqualPasswords = function (user) {

            if (user && user.password && user.password == user.passwordTwice) {
                return true;
            }

            return false;
        };

        return obj;
    }]).factory("dateServices", ['$http', '$location', '$route',
    function ($http, $location, $route) {
        var obj = {};

        obj.inlineOptions = {
            customClass: getDayClass,
            minDate: new Date(),
            showWeeks: true
        };

        obj.dateOptions = {
            dateDisabled: disabled,
            formatYear: 'yy',
            maxDate: new Date(2020, 5, 22),
            minDate: new Date(),
            startingDay: 1
        };

        obj.openDatePickerModal = function () {
            obj.isOpenDatePicker.opened = true;
        };

        obj.isOpenDatePicker = {
            opened: false
        };

        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        var afterTomorrow = new Date();
        afterTomorrow.setDate(tomorrow.getDate() + 1);

        obj.events = [
            {
                date: tomorrow,
                status: 'full'
            },
            {
                date: afterTomorrow,
                status: 'partially'
            }
        ];

        // Disable weekend selection
        function disabled(data) {
            var date = data.date,
                    mode = data.mode;
            return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
        }

        function getDayClass(data) {
            var date = data.date,
                    mode = data.mode;
            if (mode === 'day') {
                var dayToCheck = new Date(date).setHours(0, 0, 0, 0);

                for (var i = 0; i < obj.events.length; i++) {
                    var currentDay = new Date(obj.events[i].date).setHours(0, 0, 0, 0);

                    if (dayToCheck === currentDay) {
                        return obj.events[i].status;
                    }
                }
            }

            return '';
        }

        return obj;
    }]).factory("authService", ['$http', '$location', '$route', '$cookies',
    function ($http, $location, $route, $cookies) {
        var obj = {};

        obj.accessData = 'accessData';

        obj.setAuthData = function (authData) {
            $cookies.putObject(this.accessData, authData);
        };

        obj.getAccessToken = function () {
            var accessData = $cookies.getObject(this.accessData);

            if (accessData) {
                return accessData['accessToken'];
            }
        };

        obj.getCurrentUserId = function () {
            var accessData = $cookies.getObject(this.accessData);

            if (accessData) {
                return accessData['id'];
            }
        };

        obj.deleteAuthData = function () {
            var result = $cookies.remove(this.accessData);

            return result;
        };

        obj.getApiRoute = function (action) {
            var apiRoute = serviceBase + action + '?access-token=' + this.getAccessToken();

            return apiRoute;
        };

        obj.isAuth = function () {

            if (this.getAccessToken()) {
                return true;
            }

            return false;
        };

        obj.logout = function () {
            return $http.get(serviceBase + 'auths')
                    .then(successHandler)
                    .catch(errorHandler);

            function successHandler(result) {

                obj.deleteAuthData();
                $location.path('/site/index');
            }
            function errorHandler(result) {

                alert("Error data")
                $location.path('/user/index')
            }
        };

        return obj;
    }]).factory("entryService", ['$http', '$location', '$route', '$cookies', 'authService',
    function ($http, $location, $route, $cookies, authService) {
         var obj = {};
         
        obj.getUserEntries = function (userId) {
            
            if (!userId) {
                return [];
            }
            
            return $http.get(authService.getApiRoute('entries/' + userId));
        };
        
        return obj;
    }]);