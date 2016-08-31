/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'configs'], function (angular, app, $, configs) {

	return app.controller("PersonController", function ($scope, $log, $q, $timeout, $http, $interval, $location) {

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
                $location.path('#!/')
            }
        }
            
	});	
});