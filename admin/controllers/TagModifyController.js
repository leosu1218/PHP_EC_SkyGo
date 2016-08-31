/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], function (angular, app, createController, configs) {

	return app.controller("TagModifyController", createController(function ($scope, $routeParams, $http) {

		$scope.$watch("categoryTagOne", function(tag) {
			if(tag){
				tag.id(1);

			}
		});

		$scope.$watch("categoryTagTwo", function(tag) {
			if(tag){
				tag.id(2);
			}
		});

		$scope.$watch("categoryTagThree", function(tag) {
			if(tag){
				tag.id(3);
			}
		});

	}));
	
});