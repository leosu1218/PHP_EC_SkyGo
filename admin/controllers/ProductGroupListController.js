/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'jquery'], 
	function (angular, app, createController, configs, $) {

	return app.controller("ProductGroupListController", 
		createController(function ($scope, $routeParams, $http, $location) {

			$scope.$watch("tree", function(tree){
				if(tree){
					tree.bindMutipleSelectedApplyHandler(function(node){
						return false;
					});
					tree.bindSingleSelectedApplyHandler(function(node){
						return false;
					});
					tree.isApplyAppend(true);
					tree.isApplyDelete(true);
				}
			});

	}));
	
});