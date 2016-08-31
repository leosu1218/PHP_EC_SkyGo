
/*global define*/
'use strict';

define(['angular', 'app', 'configs', 'message', 'libs/PermissionDataBuilder'], function (angular, app, configs, message, PermissionDataBuilder) {

	/**
	*	Full Permission Section directive.
	*
	*
	*/
	app.directive("fullPermissionSection", function () {
		return {
			restrict: "E",
			// replace: true,
			// transclude: true,
			templateUrl: app.applicationPath + "/views/PermissionSectionDirective.html",
			controller:  function($scope) {

				var builder 			 = new PermissionDataBuilder();					
				$scope.permissionColumns = builder.export([
					{permission_id: 42},
					{permission_id: 44},
					{permission_id: 40},

					{permission_id: 88},
					{permission_id: 89},
					{permission_id: 85},
					{permission_id: 86},
					{permission_id: 87},
					{permission_id: 81},

					{permission_id: 22},
					{permission_id: 24},
					{permission_id: 26},
					{permission_id: 20},
					
					{permission_id: 60},
					{permission_id: 62},
					{permission_id: 61},					
				]);
									
				$scope.bindGet = function() {
					return builder.getSeletedAttribute($scope.permissionColumns, "id");
				}				
			},
			scope: {
				bindGet: "=?bindGet",
			},
		};
	});
});