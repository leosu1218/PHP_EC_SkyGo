/*global require*/
'use strict';

require.config({
	// baseUrl: 'js',
	paths: {
		
		jquery 		: '../common/libs/jquery-2.1.4.min',
        jqueryui 	: '../admin/libs/components/jquery-ui-1.11.4/jquery-ui.min',
        timepicker 	: '../admin/libs/components/jquery-ui-1.11.4/jquery-ui-timepicker-addon',
        sliderAccess 	: '../admin/libs/components/jquery-ui-1.11.4/jquery-ui-sliderAccess',
		text 		: '../common/libs/require-text',
		bootstrap 	: '../common/libs/bootstrap.min',
		angular 	: '../common/libs/angular-1.3.0.min',
		ngAnimate 	: '../common/libs/angular-animate-1.3.0.min',
		ngCookies 	: '../common/libs/angular-cookies-1.3.0.min',
        ngCookiesHelper : '../common/libs/angular-cookies-helper-1.0',
		ngResource 	: '../common/libs/angular-resource-1.3.0.min',
		ngRoute 	: '../common/libs/angular-route-1.3.0.min',
		ngSanitize 	: '../common/libs/angular-sanitize-1.3.0.min',
		ngTouch 	: '../common/libs/angular-touch-1.3.0.min',
		ngBootstrap : '../common/libs/ui-bootstrap-tpls-0.11.2',
		message		: '../common/libs/common-messages',
		configs		: '../common/libs/configs',
		datetime	: '../common/libs/datetime-helper',
		
		metisMenu	: '../common/libs/metisMenu/dist/metisMenu-min',

		raphael		: '../admin/libs/components/raphael/raphael-min',
		morris 		: '../admin/libs/components/morrisjs/morris.min',
		morrisData 	: '../admin/libs/componentsData/morris-data',
		ngFileUpload : '../admin/libs/ng-file-upload',
		ngFileUploadShim : '../admin/libs/ng-file-upload-shim',
		ngTree : '../admin/libs/angular-ui-tree',

		createController 	: '../admin/controllers/CreateController',
		
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
        jqueryui 		: { exports: 'jqueryui' ,  deps: ['jquery'] },
		bootstrap 	: { exports: 'bootstrap', 	deps: ['jquery'] },
		angular 	: { exports: 'angular', },
		ngAnimate 	: { exports: 'ngAnimate', 	deps: ['angular'] },
		ngCookies 	: { exports: 'ngCookies', 	deps: ['angular'] },
        ngCookiesHelper: { exports: 'ngCookiesHelper', 	deps: ['ngCookies'] },
		ngResource 	: { exports: 'ngResource', 	deps: ['angular'] },
		ngRoute 	: { exports: 'ngRoute', 	deps: ['angular'] },
		ngSanitize 	: { exports: 'ngSanitize', 	deps: ['angular'] },
		ngTouch 	: { exports: 'ngTouch', 	deps: ['angular'] },
        ngBootstrap : { exports: 'ngBootstrap', deps: ['angular'] },
        ngFileUpload: { exports: 'ngFileUpload', deps: ['angular'] },
        ngFileUploadShim: { exports: 'ngFileUploadShim', deps: ['angular'] },
        ngTree 		: { exports: 'ngTree', deps: ['angular'] },

        //timepicker
        timepicker 		: { exports: 'timepicker' ,  deps: ['jqueryui'] },
        sliderAccess 		: { exports: 'sliderAccess' ,  deps: ['jqueryui'] },

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
	    // Dependencies lib
		'angular', 
		'ngRoute',
		'ngBootstrap',
		'ngSanitize',
		'ngAnimate',
        'ngCookies',
        'ngCookiesHelper',
		'bootstrap',
		'ngFileUpload',
		'ngFileUploadShim',	
		'ngTree',	
		'app',
		'Router',
        'jquery',
        'jqueryui',
        'timepicker',
        //'sliderAccess',

		// Common Directives
        'directives/SbAlert/controller',
        'directives/SbModal/controller',
        'directives/SbSmartTable/controller',
        'directives/SbPagination/controller',
        'directives/SbUpload/controller',
        'directives/Compile/controller',
        'directives/SbTree/controller',
        'directives/SbSmartTree/controller',
        'directives/SbEditor/controller',

        // App Directives
        'directives/CompleteReturnedForm/controller',
        'directives/WholesaleProductGroupList/controller',
        'directives/WholesaleProductGroupSelect/controller',
        'directives/WholesaleProductList/controller',
        'directives/UnifiedOrderList/controller',
        'directives/UnifiedReturnedList/controller',
        'directives/AdminOrderList/controller',
        'directives/AdminReturnedList/controller',
        'directives/OrderSpecList/controller',
        'directives/SbHeader/controller',
        'directives/SbFooter/controller',
        'directives/ProductGroupTree/controller',
        'directives/FareList/controller',
        'directives/FareListSelect/controller',
        'directives/AdminImageList/controller',
        'directives/CategoryTagForm/controller',
        'directives/ProductGroupUseSmartTree/controller',
        'directives/SearchTable/controller',
        'directives/PermissionList/controller',
        'directives/DatetimePicker/controller',
        'directives/UrlInput/controller',
        
        'controllers/PermissionColumnController',
		'controllers/GroupPermissionSectionDirectiveController',
		'controllers/FullPermissionSectionDirectiveController',
		'controllers/ManagementController',

	], 
	function (angular) {
		var AppRoot = angular.element(document.getElementById('ng-app'));
		AppRoot.attr('ng-controller','AppController');
		angular.bootstrap(document, ['app']);
	}
);