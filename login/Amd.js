/*global require*/
'use strict';

require.config({
	// baseUrl: 'js',
	paths: {
		
		jquery 		: '../common/libs/jquery-2.1.4.min',
		bootstrap 	: '../common/libs/bootstrap.min',
		angular 	: '../common/libs/angular-1.3.0.min',
		ngAnimate 	: '../common/libs/angular-animate-1.3.0.min',
		ngCookies 	: '../common/libs/angular-cookies-1.3.0.min',
		ngResource 	: '../common/libs/angular-resource-1.3.0.min',
		ngRoute 	: '../common/libs/angular-route-1.3.0.min',
		ngSanitize 	: '../common/libs/angular-sanitize-1.3.0.min',
		ngTouch 	: '../common/libs/angular-touch-1.3.0.min',
		ngBootstrap : '../common/libs/ui-bootstrap-tpls-0.11.2',
		message		: '../common/libs/common-messages',
		configs		: '../common/libs/configs',
		
		metisMenu	: '../common/libs/metisMenu/dist/metisMenu-min',		

		raphael		: '../admin/libs/components/raphael/raphael-min',
		morris 		: '../admin/libs/components/morrisjs/morris.min',
		morrisData 	: '../admin/libs/componentsData/morris-data',		
		
		Class 		: '../common/libs/ooad-class-1.0',		
		
		app 		: 'App',
		common 		: '../common',
		views 		: 'views',
		controllers	: 'controllers',
	},
	shim: {
		jquery 		: { exports: '$'},
		bootstrap 	: { exports: 'bootstrap', 	deps: ['jquery'] },
		angular 	: { exports: 'angular', },
		ngAnimate 	: { exports: 'ngAnimate', 	deps: ['angular'] },
		ngCookies 	: { exports: 'ngCookies', 	deps: ['angular'] },
		ngResource 	: { exports: 'ngResource', 	deps: ['angular'] },
		ngRoute 	: { exports: 'ngRoute', 	deps: ['angular'] },
		ngSanitize 	: { exports: 'ngSanitize', 	deps: ['angular'] },
		ngTouch 	: { exports: 'ngTouch', 	deps: ['angular'] },
        ngBootstrap : { exports: 'ngBootstrap', deps: ['angular'] },

        // Sb ui modules.
        metisMenu   : { exports: 'metisMenu', 	deps: ['jquery'] },        

        // Extends angularJs tools.
        Class: { exports: 'Class' },        
	}
});

require(
	[
	// Dependencies from lib
		'angular', 
		'ngRoute',
		'ngBootstrap',
		'ngSanitize',
		'ngAnimate',
		'bootstrap',
	// Angular directives/controllers/services
		'app',
		'Router',
		'common/controllers/SbHeaderController',
		'common/controllers/SbFooterController',
		'common/controllers/SbPagenationController'
	], 
	function (angular) {
		var AppRoot = angular.element(document.getElementById('ng-app'));
		AppRoot.attr('ng-controller','AppController');
		angular.bootstrap(document, ['app']);
	}
);