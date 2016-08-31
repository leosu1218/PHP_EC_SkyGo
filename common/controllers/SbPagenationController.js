/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	app.directive("pagenation", function () {
		return {
			restrict: "E",
			templateUrl: app.commonPath + "/views/sb-pagenation.html",
			scope: {
		      info: '=info'
		    },
			controller:  'PagenationController'
		};
	});

	app.controller("PagenationController", function ($scope, $location, $rootScope) {
		
		$scope.pathArray = $location.path().split("/");
		$scope.textData = {
			totalNumber: 0,
			rangeTopNumber:0,
			rangeButtomNumber:0
		};

		$rootScope.$on('notify', function(event, msg) {
			
		});

		$scope.pageData = [
			{
				index:'1',
				path:"#!"+$scope.pathArray.join("/")
			},{
				index:'2',
				path:"#!"+$scope.pathArray.join("/")
			}
		];
		

	});
	
});