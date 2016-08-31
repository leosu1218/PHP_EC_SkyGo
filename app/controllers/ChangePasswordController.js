/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'slick', 'configs'], function (angular, app, $, slick, configs) {

	return app.controller("ChangePasswordController", function ($scope, $location, $http, $interval, $cookiesHelper) {
		
        $scope.$watch("oauth", function(oauth) {
            if(oauth) {
                if($scope.oauth.result == "success") {
                }
                else {
                    $scope.loginViewShow.show(closeWindow);
                }
            }
        });

        function closeWindow(){
            if($scope.oauth.result != "success"){
                $scope.loginViewShow.hide();
            }
        }
        
        $scope.changePassword = function(){
            if ($scope.newpassword == null || $scope.newpasswordTwo == null) {
                alert("請輸入密碼");
            }else if ($scope.newpassword != $scope.newpasswordTwo) {
                alert("請確認新密碼相同");
            }else {
                var url = configs.api.oauth + "/user/check";
                var req = {
                    method: 'POST',
                    headers: configs.api.headers,
                    data: { "account": $scope.oauth.email, 
                            "password": $scope.password,
                            "newpassword": $scope.newpassword,
                            "id": $scope.oauth.id
                        },
                    url: url
                };
                $http(req).success(function(data) {
                    alert("修改成功");
                    $location.path('#!/')
                }).error(function() {
                    alert("舊密碼錯誤");
                });
            }
        };

	});	
});