/*global define*/
'use strict';

// set up base routes
define(['angular', 'app',
	'controllers/HomeController',
	'controllers/LoginController',
	'controllers/GroupListController',
	'controllers/CreateGroupController',
	'controllers/CreateUserController',
	'controllers/UserListController',
	'controllers/GroupUserListController',
	'controllers/ModifyUserPermissionController',
	'controllers/ModifySelfPasswordController',
	'controllers/ProductGroupListController',
	'controllers/CreateGroupBuyingMasterController',
	'controllers/GroupBuyingMasterController',
	'controllers/GroupBuyingMasterListController',
	'controllers/GroupBuyingActivityListController',
	'controllers/GroupBuyingActivityController',
	'controllers/CreateWholesaleProductController',
	'controllers/CreateRetailProductController',
	'controllers/ProductListController',
    'controllers/WholesaleProductController',
    'controllers/GeneralOrderListController',
    'controllers/GeneralReturnedListController',
    'controllers/GeneralActivityListController',
    'controllers/GeneralActivityListController',
    'controllers/CreateGeneralActivityController',
    'controllers/GeneralActivityController',
    'controllers/HomePageController',
    'controllers/AdvertisementController',
    'controllers/SystemConfigController',
    'controllers/TagModifyController',
    'controllers/ConsumerUserController',
    'controllers/ConsumerOrderListController',
    'controllers/ModifyUserGroupPermissionController',
    'controllers/ReimburseListController',
    'controllers/ReimburseDetailController',
    

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
			.when('/login',
				{ 
					templateUrl: currentPath('/Login.html'),
					controller: 'LoginController' 
				})			
			.when('/group/list/:pageNo/:pageSize',
				{ 
					templateUrl: currentPath('/GroupList.html'),
					controller: 'GroupListController' 
				})			
			.when('/user/list/:pageNo/:pageSize',
				{ 
					templateUrl: currentPath('/UserList.html'),
					controller: 'UserListController' 
				})
			.when('/group/:id/user/list/:pageNo/:pageSize',
				{
					templateUrl: currentPath('/GroupUserList.html'),
					controller: 'GroupUserListController' 
				})
			.when('/group/create',
				{ 
					templateUrl: currentPath('/CreateGroup.html'),
					controller: 'CreateGroupController'
				})
			.when('/group/:id',
				{
					templateUrl: currentPath('/ModifyUserGroupPermission.html'),
					controller: 'ModifyUserGroupPermissionController'
				})
			.when('/user/create', 			
				{ 
					templateUrl: currentPath('/CreateUser.html'),
					controller: 'CreateUserController'
				})
			.when('/user/:userId',
				{ 
					templateUrl: currentPath('/ModifyUserPermission.html'),
					controller: 'ModifyUserPermissionController'
				})
		
			.when('/admin/password/modify',	
				{ 
					templateUrl: currentPath('/ModifySelfPassword.html'), 
					controller: 'ModifySelfPasswordController'
				})
			.when('/product/group/:channel/list/:groupType/:pageNo/:pageSize',
				{ 
					templateUrl: currentPath('/ProductGroupList.html'),
					controller: 'ProductGroupListController'
				})

            //consumer user
            .when('/comsumer/list',
            {
                templateUrl: currentPath('/ConsumerUser.html'),
                controller: 'ConsumerUserController'
            })
            .when('/comsumer/order/list/:id',
            {
                templateUrl: currentPath('/ConsumerOrderList.html'),
                controller: 'ConsumerOrderListController'
            })

			// Group Buying
			.when('/groupbuying/master/list/:pageNo/:pageSize',
				{ 
					templateUrl: currentPath('/GroupBuyingMasterList.html'), 
					controller: 'GroupBuyingMasterListController'
				})
			.when('/groupbuying/master/create',
				{ 
					templateUrl: currentPath('/CreateGroupBuyingMaster.html'), 
					controller: 'CreateGroupBuyingMasterController'
				})
			.when('/groupbuying/master/:id',
				{ 
					templateUrl: currentPath('/GroupBuyingMaster.html'), 
					controller: 'GroupBuyingMasterController'
				})
			.when('/groupbuying/activity/list/:pageNo/:pageSize',
				{ 
					templateUrl: currentPath('/GroupBuyingActivityList.html'), 
					controller: 'GroupBuyingActivityListController'
				})
			.when('/groupbuying/activity/:id',
				{
					templateUrl: currentPath('/GroupBuyingActivity.html'),
					controller: 'GroupBuyingActivityController'
				})
			.when('/product/wholesale/create',
				{ 
					templateUrl: currentPath('/WholesaleProductCreate.html'),
					controller: 'CreateWholesaleProductController'
				})
			.when('/product/retail/create',
				{ 
					templateUrl: currentPath('/RetailProductCreate.html'),
					controller: 'CreateRetailProductController'
				})
			.when('/product/:channel/list/:pageNo/:pageSize',
				{ 
					templateUrl: currentPath('/ProductList.html'),
					controller: 'ProductListController'
				})
			.when('/product/wholesale/:id',
				{
					templateUrl: currentPath('/WholesaleProduct.html'),
					controller: 'WholesaleProductController'
				})
            .when('/general/order/list',
                {
                    templateUrl: currentPath('/GeneralOrderList.html'),
                    controller: 'GeneralOrderListController'
                })
            .when('/general/returned/list',
                {
                    templateUrl: currentPath('/GeneralReturnedList.html'),
                    controller: 'GeneralReturnedListController'
                })
            .when('/general/activity/list',
                {
                    templateUrl: currentPath('/GeneralActivityList.html'),
                    controller: 'GeneralActivityListController'
                })
            .when('/general/activity/create',
                {
                    templateUrl: currentPath('/CreateGeneralActivity.html'),
                    controller: 'CreateGeneralActivityController'
                })
            .when('/general/activity/:id',
                {
                    templateUrl: currentPath('/GeneralActivity.html'),
                    controller: 'GeneralActivityController'
                })
            .when('/system/config',
                {
                    templateUrl: currentPath('/SystemConfig.html'),
                    controller: 'SystemConfigController'
                })

            //video platform
            .when('/website/homepage',
                {
                    templateUrl: currentPath('/HomePage.html'),
                    controller: 'HomePageController'
                })

            .when('/website/promotion',
            {
                templateUrl: currentPath('/Ad.html'),
                controller: 'AdvertisementController'
            })

            .when('/video/platform/modify/tag',
                {
                    templateUrl: currentPath('/TagModify.html'),
                    controller: 'TagModifyController'

                })

            .when('/management/:querystring',
                {
                    templateUrl: currentPath('/Management.html'),
                    controller: 'ManagementController'

                })

            .when('/reimburse/detail/:querystring',
                {
                    templateUrl: currentPath('/ReimburseDetail.html'),
                    controller: 'ReimburseDetailController'

                })

            .when('/reimburse/list',
            {
                templateUrl: currentPath('/ReimburseList.html'),
                controller: 'ReimburseListController'

            })
			
			.otherwise({redirectTo: '/'});

	}]);
	
});