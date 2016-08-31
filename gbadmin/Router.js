/*global define*/
'use strict';

// set up base routes
define(['angular', 'app',
	'controllers/HomeController',
	'controllers/ActivityListController',
	'controllers/ActivityController',
	'controllers/CreateActivityController',
], function (angular, app) {

	return app.config([ '$routeProvider', function ($routeProvider) {		

		function currentPath(path) {
			return app.applicationPath + '/views' + path;
		}

		$routeProvider
			.when('/',
				{ 
					templateUrl: currentPath('/Home.html'),
					controller: 'HomeController' 
				})			
			.when('/activity/list/:pageNo/:pageSize',
				{ 
					templateUrl: currentPath('/ActivityList.html'), 
					controller: 'ActivityListController'
				})			
			.when('/activity/create', 
				{
					templateUrl: currentPath('/CreateActivity.html'),
					controller: 'CreateActivityController'
				})
			.when('/activity/:id',
				{ 
					templateUrl: currentPath('/Activity.html'), 
					controller: 'ActivityController'
				})
			.otherwise({redirectTo: '/'});

	}]);
	
});