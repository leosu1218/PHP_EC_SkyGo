/*global require*/
'use strict';

require.config({
	// baseUrl: 'js',
	paths: {
		
		jquery : '../common/libs/jquery-1.10.1.min',
		jqueryui 	: '../common/libs/jquery-ui-1.11.4/jquery-ui.min',
        text 		: '../common/libs/require-text',
        datetime 		: '../common/libs/datetime-helper',
		bootstrap : '../common/libs/bootstrap.min',
        configs		: '../common/libs/configs',
		angular : '../common/libs/angular.ie.min',
		ngAnimate : '../common/libs/angular-animate.min',
		ngCookies : '../common/libs/angular-cookies.min',
        ngCookiesHelper : '../common/libs/angular-cookies-helper-1.1',
        ngCartService : 'libs/skygo-cart-service',
		ngResource : '../common/libs/angular-resource.min',
		ngRoute : '../common/libs/angular-route.min',
		ngSanitize : '../common/libs/angular-sanitize.min',
		ngTouch : '../common/libs/angular-touch.min',
		ngBootstrap : '../common/libs/ui-bootstrap-tpls-0.11.2',
		jqueryMigrate: '../common/libs/jquery-migrate-1.2.1.min',
		slick: '../common/libs/slick.min',
        bloggerContext:'../json/bloggerContext',
        goAnalytics:'../common/libs/google-analytics',
        //yaAnalytics:'../common/libs/yahoo-analytics',

        shim:'http://cdnjs.cloudflare.com/ajax/libs/es5-shim/4.0.5/es5-shim.min',
        // html5shiv: 'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min',
        // respond: 'https://oss.maxcdn.com/respond/1.4.2/respond.min',

		app: 'App',

		common: '../common',
		views: 'views',
		controllers: 'controllers',
	},
	shim: {
        jquery: { exports: '$'},
        jqueryui: { exports: 'jqueryui' ,  deps: ['jquery'] },
        shim: { exports: 'shim'},
        html5shiv: { exports: 'html5shiv'},
        respond: { exports: 'respond'},
        datetime: { exports: 'datetime'},
		bootstrap: { exports: 'bootstrap', deps: ['jquery'] },
		angular: { exports: 'angular', deps: ['jquery', 'shim']  },
		ngAnimate: { exports: 'ngAnimate', deps: ['angular'] },
		ngCookies: { exports: 'ngCookies', deps: ['angular'] },
        ngCookiesHelper: { exports: 'ngCookiesHelper', 	deps: ['ngCookies'] },
		ngResource: { exports: 'ngResource', deps: ['angular'] },
		ngRoute: { exports: 'ngRoute', deps: ['angular'] },
		ngSanitize: { exports: 'ngSanitize', deps: ['angular'] },
		ngTouch: { exports: 'ngTouch', deps: ['angular'] },
        ngBootstrap: { exports: 'ngBootstrap', deps: ['angular'] },
        jqueryMigrate: { exports: 'jqueryMigrate', deps: ['jquery'] },
        slick: { exports: 'slick', deps: ['jqueryMigrate'] },
        goAnalytics: { exports: 'goAnalytics' },
        //yaAnalytics: { exports: 'yaAnalytics' }
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
        'ngCookies',
        'ngCookiesHelper',
        'ngCartService',
		'jquery',
		'jqueryui',
		'bootstrap',
		'jqueryMigrate',
		'slick',
	// Angular directives/controllers/services
		'app',
		'Router',
        'goAnalytics',
        //'yaAnalytics',

        // application's directives
		'directives/Header/controller',
        'directives/Footer/controller',
        'directives/Compile/controller',
        'directives/SbAlert/controller',
        'directives/SkyGoPagination/controller',
        'directives/ProductLeftList/controller',
        'directives/SlickSlider/controller',
        'directives/Blogger/controller',
        'directives/Promotion/controller',
        'directives/Maintain/controller',

        'directives/CartStateBar/controller',
        'directives/CheckCartItems/controller',
        'directives/CartBuySuccess/controller',
        'directives/CartHowToPay/controller',
        'directives/CartOrderForm/controller',
        'directives/GotoPayForm/controller',
        'directives/UserLogin/controller',
        'directives/Member/controller',
        'directives/DatetimePicker/controller',

        'controllers/AboutController',
        'controllers/HomeController',
        'controllers/OAuthResultController',
        'controllers/ShoppingCartController',
		'controllers/LoginController',
		'controllers/ProductController',
		'controllers/ProductListController',
		'controllers/GroupBuyingController',
		'controllers/OrderController',
		'controllers/SearchOrderController',
		'controllers/BloggerController',
		'controllers/ShoppingGuideController',
		'controllers/MemberProblemController',
		'controllers/DeliveryProblemController',
		'controllers/ProcessController',
        'controllers/PrivacyController',
        'controllers/PaymentResultController',
        'controllers/ChangePasswordController',
        'controllers/ProvisionController',
        'controllers/HowOrderController',
        'controllers/OtherProblemController',
        'controllers/OrderProblemController',
        'controllers/AftermarketCcontroller',
        'controllers/SafeController',
        'controllers/ProductPageController',
        'controllers/PersonalInformationController',
        'controllers/PersonController',
		
        // examples
        'controllers/CookiesWriterController',
        'controllers/CookiesReaderController',
        'controllers/SliderController'
	], 
	function (angular) {
		var AppRoot = angular.element(document.getElementById('ng-app'));
		AppRoot.attr('ng-controller','AppController');
		angular.bootstrap(document, ['app']);
	}
);