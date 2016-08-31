/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs'], 
	function (angular, app, createController, message, configs) {

	return app.controller("GroupUserListController", createController(function($scope , $routeParams, $http){
		
		$scope.users = [];
		$scope.group = "";
				
		/**
		*	Fetch user info from server by api
		*	and bind view.
		*		
		*	@param pageNo int The record's page number
		*	@param pageSize int The record's page size
		*/
		var getList = function(id, pageNo, pageSize) {	
			var request = {
				method: 'GET',
			 	url: configs.api.platformUserGroup + "/" + id + "/user/list/" + pageNo + "/" + pageSize,
			 	headers: configs.api.headers,
			 	data: {},		 	
			}

			$http(request).success(function(data, status, headers, config) {				
				$scope.users = data.records;
				$scope.group = data.name;
			}).error(function(data, status, headers, config){				
				alert("取得列表發生錯誤");
			});
		}
		
		getList($routeParams.id, $routeParams.pageNo, $routeParams.pageSize);

	}));
	
});