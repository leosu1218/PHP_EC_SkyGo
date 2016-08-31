/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	app.directive("commonHeader", function () {
		return {
			restrict: "EA",
			replace: true,
			transclude: true,
			templateUrl: app.applicationPath + "/views/Header.html",
			controller:  'HeaderController',
			scope: {								
				outerInstance: "=?instance",				
			},
		};
	});

	app.controller("HeaderController", function ($scope, $location) {	

		$scope.style = [' color:red; ', '', ''];
		$scope.index = 0;

		$scope.active = function (index) {
			$scope.style[$scope.index] = '';
			$scope.style[index] = ' color:red; ';
			$scope.index = index;
		}

        $scope.outerInstance = {
        	setTitle: function(title) {
        		$scope.title = title;
        	}
        };
	});
	
});