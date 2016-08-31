/*global define*/
'use strict';

// set up base routes
define(['angular', 'app'], function (angular, app) {

	return app.config([ '$routeProvider', function ($routeProvider) {		

		function currentPath(path) {
			return app.applicationPath + '/views' + path;
		}

		$routeProvider
			.when('/', {templateUrl: currentPath('/Home.html'), controller: 'HomeController'})
            .when('/about', {templateUrl: currentPath('/About.html'), controller: 'AboutController'})
            .when('/login', {templateUrl: currentPath('/Login.html'), controller: 'LoginController'})
            .when('/oauth/:action/:result/:name', {templateUrl: currentPath('/OAuthResult.html'), controller: 'OAuthResultController'})

            .when('/reader', {templateUrl: currentPath('/Reader.html'), controller: 'CookiesReaderController'})
            .when('/writer', {templateUrl: currentPath('/Writer.html'), controller: 'CookiesWriterController'})

			.when('/productlist/:keyword', {templateUrl: currentPath('/Productlist.html'), controller: 'ProductListController'})
			.when('/productlist/:tagid/:tag1', {templateUrl: currentPath('/Productlist.html'), controller: 'ProductListController'})
            .when('/productlist/:tagid/:tag1/:tag2', {templateUrl: currentPath('/Productlist.html'), controller: 'ProductListController'})
            .when('/productlist/:tagid/:tag1/:tag2/:tag3', {templateUrl: currentPath('/Productlist.html'), controller: 'ProductListController'})
            .when('/product/:tagid/:id', {templateUrl: currentPath('/Product.html'), controller: 'ProductController'})
            .when('/product/:tagid/:tag1/:id', {templateUrl: currentPath('/Product.html'), controller: 'ProductController'})
            .when('/product/:tagid/:tag1/:tag2/:id', {templateUrl: currentPath('/Product.html'), controller: 'ProductController'})
            .when('/product/:tagid/:tag1/:tag2/:tag3/:id', {templateUrl: currentPath('/Product.html'), controller: 'ProductController'})
			.when('/groupbuying', {templateUrl: currentPath('/Groupbuying.html'), controller: 'GroupBuyingController'})
			.when('/shoppingcart', {templateUrl: currentPath('/Shoppingcart.html'), controller: 'ShoppingCartController'})
			.when('/order', {templateUrl: currentPath('/Order.html'), controller: 'OrderController'})
			.when('/searchorder/:serial/:ids', {templateUrl: currentPath('/SearchOrder.html'), controller: 'SearchOrderController'})
			.when('/shoppingguide', {templateUrl: currentPath('/ShoppingGuide.html'), controller: 'ShoppingGuideController'})
			.when('/memberproblem', {templateUrl: currentPath('/MemberProblem.html'), controller: 'MemberProblemController'})
			.when('/deliveryproblem', {templateUrl: currentPath('/DeliveryProblem.html'), controller: 'DeliveryProblemController'})
			.when('/process', {templateUrl: currentPath('/Process.html'), controller: 'ProcessController'})
			.when('/privacy', {templateUrl: currentPath('/Privacy.html'), controller: 'PrivacyController'})
            .when('/contact', {templateUrl: currentPath('/Contact.html')})
            .when('/slider', {templateUrl: currentPath('/Slider.html'), controller: 'SliderController'})
            .when('/test', {templateUrl: currentPath('/test.html'), controller: 'TestController'})
            .when('/payment/:result', {templateUrl: currentPath('/PaymentResult.html'), controller: 'PaymentResultController'})
            .when('/provision', {templateUrl: currentPath('/Provision.html'), controller: 'ProvisionController'})
            .when('/howorder', {templateUrl: currentPath('/HowOrder.html'), controller: 'HowOrderController'})
            .when('/otherproblem', {templateUrl: currentPath('/OtherProblem.html'), controller: 'OtherProblemController'})
            .when('/orderproblem', {templateUrl: currentPath('/OrderProblem.html'), controller: 'OrderProblemController'})
            .when('/aftermarket', {templateUrl: currentPath('/Aftermarket.html'), controller: 'AftermarketCcontroller'})
            .when('/safe', {templateUrl: currentPath('/Safe.html'), controller: 'SafeController'})
            .when('/productpage/:id', {templateUrl: currentPath('/ProductPage.html'), controller: 'ProductPageController'})


			.when('/blogger', {templateUrl: currentPath('/Blogger.html'), controller: 'BloggerController'})
			.when('/changepassword', {templateUrl: currentPath('/ChangePassword.html'), controller: 'ChangePasswordController'})
			.when('/personalinformation', {templateUrl: currentPath('/PersonalInformation.html'), controller: 'PersonalInformationController'})
			.when('/person', {templateUrl: currentPath('/Person.html'), controller: 'PersonController'})

			.otherwise({redirectTo: '/'});

	}]);
	
});