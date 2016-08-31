/*global define*/
'use strict';

define(['angular', 'app', 'configs'], function (angular, app, configs) {

	return app.controller("LoginController", function ($scope, $http, $cookiesHelper) {

        $cookiesHelper.register($scope, "oauth", "oauth", true);
        $scope.register = {};

        /**
         * Get error message from check post params.
         * @returns {string} return "" when error not found.
         */
        function errorOfRegister() {
            if($scope.register.password != $scope.register.checkPassword) {
                return "您第一次輸入的密碼與第二次輸入的不同, 請重新確認";
            }
            return "";
        }

        /**
         * User register new account.
         */
        $scope.userRegister = function() {
            var error = errorOfRegister();

            if(error == "") {
                var url = configs.api.oauth + "/user/register";
                var req = {
                    method: 'POST',
                    headers: configs.api.headers,
                    data: {
                        account: $scope.register.email,
                        password: $scope.register.password,
                        email: $scope.register.email,
                        name: $scope.register.name
                    },
                    url: url
                };
                $http(req).success(function(data) {
                    $scope.oauth = {
                        result: "success",
                        name: data.info.name,
                        type: data.type,
                        id: data.info.id
                    };
                }).error(function() {
                    $scope.alert.show("註冊失敗, 請重新嘗試");
                });
            }
            else {
                $scope.alert.show(error);
            }
        };

        /**
         * User login.
         */
        $scope.userLogin = function() {
            var url = configs.api.oauth + "/user/login";
            var req = {
                method: 'POST',
                headers: configs.api.headers,
                data: {"account": $scope.email, "password": $scope.password},
                url: url
            };
            $http(req).success(function(data) {
                $scope.oauth = {
                    result: "success",
                    name: data.info.name,
                    type: data.type,
                    id: data.info.id
                };
            }).error(function() {
                $scope.alert.show("帳號或密碼錯誤, 請重新嘗試");
            });
        }
	});
});