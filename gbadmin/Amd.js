/*global require*/
'use strict';

require.config({
	// baseUrl: 'js',
	paths: {
		jquery 		: '../common/libs/jquery-2.1.4.min',
        text 		: '../common/libs/require-text',
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
		datetime	: '../common/libs/datetime-helper',
		
		metisMenu	: '../common/libs/metisMenu/dist/metisMenu-min',

		raphael		: '../gbadmin/libs/components/raphael/raphael-min',
		morris 		: '../gbadmin/libs/components/morrisjs/morris.min',
		morrisData 	: '../gbadmin/libs/componentsData/morris-data',

		createController 	: '../gbadmin/controllers/CreateController',
		
		Class 		: '../common/libs/ooad-class-1.0',
		ControllerCreator : '../common/libs/controller-creator-1.0',
		SbControllerCreator: 'libs/SbControllerCreator',
		
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
        ControllerCreator: { exports: 'ControllerCreator', 	deps: ['Class'] },
        SbControllerCreator: { exports: 'SbControllerCreator', 	deps: ['ControllerCreator'] }
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
		'app',
		'Router',

        // Common Directives
        'directives/SbAlert/controller',
        'directives/SbModal/controller',
        'directives/SbSmartTable/controller',
        'directives/SbPagination/controller',
        'directives/Compile/controller',

        // App Directives
        'directives/WholesaleProductGroupList/controller',
        'directives/WholesaleProductGroupSelect/controller',
        'directives/WholesaleProductList/controller',
        'directives/UnifiedOrderList/controller',
        'directives/UnifiedReturnedList/controller',
        'directives/OrderSpecList/controller',

		'controllers/SbHeaderController',
		'common/controllers/SbFooterController'
	], 
	function (angular) {
		var AppRoot = angular.element(document.getElementById('ng-app'));
		AppRoot.attr('ng-controller','AppController');
		angular.bootstrap(document, ['app']);
	}
);