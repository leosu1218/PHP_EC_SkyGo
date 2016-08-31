/*global require*/
'use strict';

require.config({
	// baseUrl: 'js',
	paths: {

		jquery : '../common/libs/jquery-1.10.1.min',
		bootstrap : '../common/libs/bootstrap.min',
		angular : '../common/libs/angular.min',
		ngAnimate : '../common/libs/angular-animate.min',
		ngCookies : '../common/libs/angular-cookies.min',
        ngCookiesHelper : '../common/libs/angular-cookies-helper-1.1',
		ngResource : '../common/libs/angular-resource.min',
		ngRoute : '../common/libs/angular-route.min',
		ngSanitize : '../common/libs/angular-sanitize.min',
		ngTouch : '../common/libs/angular-touch.min',
		ngBootstrap : '../common/libs/ui-bootstrap-tpls-0.11.2',
		ngCarousel: 'libs/angular-carousel.min',
		configs		: '../common/libs/configs',

		app: 'App',

		common: '../common',
		views: 'views',
		controllers: 'controllers',
	},
	shim: {
		jquery: { exports: '$'},
		bootstrap: { exports: 'bootstrap', deps: ['jquery'] },
		angular: { exports: 'angular', },
		ngAnimate: { exports: 'ngAnimate', deps: ['angular'] },
		ngCookies: { exports: 'ngCookies', deps: ['angular'] },
        ngCookiesHelper: { exports: 'ngCookiesHelper', 	deps: ['ngCookies'] },
		ngResource: { exports: 'ngResource', deps: ['angular'] },
		ngRoute: { exports: 'ngRoute', deps: ['angular'] },
		ngSanitize: { exports: 'ngSanitize', deps: ['angular'] },
		ngTouch: { exports: 'ngTouch', deps: ['angular'] },
        ngBootstrap: { exports: 'ngBootstrap', deps: ['angular'] },
        ngCarousel: { exports: 'ngCarousel', deps: ['angular', 'ngTouch'] },
	}
});

require(
	[
	// Dependencies from lib
		'angular', 
		'ngRoute',
		'ngBootstrap',
		'ngSanitize',
        'ngCookies',
        'ngCookiesHelper',
		'ngAnimate',
		'ngCarousel',
		'bootstrap',
	// Angular directives/controllers/services
		'app',
		'Router',
		
		'controllers/HelperController',
		'controllers/HeaderController',
		'controllers/FooterController',
		'controllers/PaymentErrorController',
		'controllers/PaymentSuccessController',
		'controllers/BsCarouselController',			
		'controllers/HomeController'
	], 
	function (angular) {
		var AppRoot = angular.element(document.getElementById('ng-app'));
		AppRoot.attr('ng-controller','AppController');
		angular.bootstrap(document, ['app']);
	}
);