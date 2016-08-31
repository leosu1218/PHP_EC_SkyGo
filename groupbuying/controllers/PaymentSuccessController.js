/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	return app.controller("PaymentSuccessController", function ($scope, $timeout, $http, $routeParams , $cookiesHelper) {
        $cookiesHelper.register($scope, "serial", "serial", true);
        console.log($scope)

	
	});	
});

