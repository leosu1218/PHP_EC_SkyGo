
/*global define*/
'use strict';

define(['angular', 'app', 'configs', 'message', 'libs/PermissionDataBuilder'], function (angular, app, configs, message, PermissionDataBuilder) {

	/**
	*	Group Permission Section directive.
	*
	*
	*/
	app.directive("groupPermissionSection", function () {
		return {
			restrict: "E",
			// replace: true,
			// transclude: true,
			templateUrl: app.applicationPath + "/views/PermissionSectionDirective.html",
			controller:  function($scope, $http) {

				$scope.permissionColumns = [];
				var builder 			 = new PermissionDataBuilder();					

				/**
				*	Fetch permission info from server by api
				*	and bind view.
				*
				*	@param id int The id of group.
				*/
				$scope.getPermission = function(id) {
			
					var request = {
						method: 'GET',
					 	url: configs.api.platformUserGroup + "/" + id + "/permission/list/1/10000" ,
					 	headers: configs.api.headers,	
					 	data: {},		 	
					}

					$http(request).success(function(data, status, headers, config){						
						$scope.permissionColumns = builder.export(data.records);						
					}).error(function(data, status, headers, config){				
						$scope.permissionColumns = [];
					});
				}
							
				$scope.bindFresh = function(id) {
					$scope.getPermission(id);
				}

				$scope.bindGet = function() {
					return builder.getSeletedAttribute($scope.permissionColumns, "group_permission_id");
				}				
			},
			scope: {								
				bindFresh: "=?bindFresh",
				bindGet: "=?bindGet",
			},
		};
	});
});