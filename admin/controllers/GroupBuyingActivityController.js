/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs', 'datetime'], 
	function (angular, app, createController, message, configs, datetime) {

	return app.controller("GroupBuyingActivityController", 
		createController(function ($scope , $routeParams, $http, $timeout) {
            $scope.searchOrder = {
                activityId: $routeParams.id,
                activityType: "groupbuying"
            }

            $scope.searchReturned = {
                activityId: $routeParams.id,
                activityType: "groupbuying"
            }
		})
	);	
});