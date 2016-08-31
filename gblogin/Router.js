/*global define*/
'use strict';

// set up base routes
define(['angular', 'app',
	'controllers/LoginController',
	
], function (angular, app) {

	return app.config([ '$routeProvider', function ($routeProvider) {		

		function currentPath(path) {
			return app.applicationPath + '/views' + path;
		}

		$routeProvider
			.when('/', 		{ templateUrl: currentPath('/login.html'), 			controller: 'LoginController' })
			.otherwise({redirectTo: '/'});

	}]);
	
});