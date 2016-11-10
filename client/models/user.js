'use strict';
exclusivepressing_user.factory("services", ['$http', '$location', '$route',
    function ($http, $location, $route) {
        var obj = {};
        obj.getUsers = function () {
            return $http.get(serviceBase + 'users');
        }
        obj.createUser = function (user) {
            return $http.post(serviceBase + 'users', user)
                    .then(successHandler)
                    .catch(errorHandler);
            function successHandler(result) {
                $location.path('/user/index');
            }
            function errorHandler(result) {
                alert("Error data")
                $location.path('/user/create')
            }
        };
        obj.getUser = function (userID) {
            return $http.get(serviceBase + 'users/' + userID);
        }

        obj.updateUser = function (user) {
            return $http.put(serviceBase + 'users/' + user.id, user)
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
            return $http.delete(serviceBase + 'users/' + userID)
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

                if (result && result.access_token) {

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
    }]);