/*global define*/
'use strict';

// set up base routes
define(['angular', 'app'], function (angular, app) {

	return app.config([ '$routeProvider', function ($routeProvider) {		

		function currentPath(path) {
			return app.applicationPath + '/views' + path;
		}

		$routeProvider
			.when('/payment/error', {templateUrl: currentPath('/PaymentError.html'), controller: 'PaymentErrorController'})
			.when('/payment/success', {templateUrl: currentPath('/PaymentSuccess.html'), controller: 'PaymentSuccessController'})
			.when('/helper/:orderSerial', {templateUrl: currentPath('/Helper.html'), controller: 'HelperController'})
			.when('/:id', {templateUrl: currentPath('/Home.html'), controller: 'HomeController'})
			.otherwise({redirectTo: '/'});

	}]);
	
});