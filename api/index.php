<?php


require_once('../configs/sys.config.inc.php');

try
{
	$PushSynature = new Synature(array(
		'SysRoot' => ROOT,
		'frameworkRoot' => FRAMEWORK_PATH,
        'logPath' => LOG_PATH,
		'router' => array(
			'ctrlRoot' => FRAMEWORK_PATH . 'controllers',
			'patterns' => array(
				
				// System Section
				array( "GET: 	/system/config/fare/list/<pageNo:\d+>/<pageSize:\d+>", 								"SystemController", "getFareList(<pageNo>,<pageSize>)"),
				array( "GET: 	/system/config/search/fare/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",			"SystemController", "searchFareList(<pageNo>,<pageSize>,<querystring>)"),
				array( "POST: 	/system/config/fare", 																"SystemController", "createFare()"),
				array( "PUT: 	/system/config/fare/<id:\d+>", 														"SystemController", "updateFare(<id>)"),
				array( "DELETE: /system/config/fare/<id:\d+>", 														"SystemController", "removeFare(<id>)"),
				array( "GET: 	/system/config/productevent/list/<pageNo:\d+>/<pageSize:\d+>", 						"SystemController", "getProductEventList(<pageNo>,<pageSize>)"),
				array( "POST: 	/system/config/productevent", 														"SystemController", "createProductEvent()"),
				array( "PUT: 	/system/config/productevent/<id:\d+>", 												"SystemController", "updateProductEvent(<id>)"),
				array( "DELETE: /system/config/productevent/<id:\d+>", 												"SystemController", "removeProductEvent(<id>)"),
				array( "GET: 	/system/config/delivery/list/<pageNo:\d+>/<pageSize:\d+>", 								"SystemController", "getDeliveryList(<pageNo>,<pageSize>)"),
				array( "GET: 	/system/config/search/delivery/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",			"SystemController", "searchDeliveryList(<pageNo>,<pageSize>,<querystring>)"),
				array( "POST: 	/system/config/delivery", 																"SystemController", "createLogistics()"),
				array( "DELETE: /system/config/delivery/<id:\d+>", 														"SystemController", "removeDelivery(<id>)"),
				array( "PUT: 	/system/config/delivery/<id:\d+>", 														"SystemController", "updateDelivery(<id>)"),

                // Fare Section
                array( "POST:    /fare/search/<activityType:\w+>/<pageNo:\d+>/<pageSize:\d+>",                        "FareController", "search(<activityType>,<pageNo>,<pageSize>)"),

				// group buying user's api
				array( "POST: 	/user/groupbuyingmaster/login", 											"GroupBuyingMasterUserController", "login()"),
				array( "GET: 	/user/groupbuyingmaster/self", 												"GroupBuyingMasterUserController", "getSelf()"),
				array( "POST: 	/user/groupbuyingmaster/logout", 											"GroupBuyingMasterUserController", "logout()"),
				array( "POST: 	/user/groupbuyingmaster/register",											"GroupBuyingMasterUserController", "register()"),

				array( "GET: 	/user/groupbuyingmaster/<id:\d+>", 											"GroupBuyingMasterUserController", "getById(<id>)"),
				array( "PUT: 	/user/groupbuyingmaster/<id:\d+>/base", 										"GroupBuyingMasterUserController", "updateBaseById(<id>)"),
				array( "PUT: 	/user/groupbuyingmaster/<id:\d+>/bank", 										"GroupBuyingMasterUserController", "updateBankById(<id>)"),
				array( "PUT: 	/user/groupbuyingmaster/<id:\d+>/account", 									"GroupBuyingMasterUserController", "updateAccountById(<id>)"),

				array( "PUT: 	/user/groupbuyingmaster/<id:\d+>/groups", 									"GroupBuyingMasterUserController", "appendGroupById(<id>)"),
				array( "DELETE: /user/groupbuyingmaster/<id:\d+>/group/<groupId:\d+>", 								"GroupBuyingMasterUserController", "removeGroupById(<id>,<groupId>)"),

                array( "PUT: 	/user/groupbuyingmaster/self/activity/list/state", 					        "GroupBuyingActivityController", "updateStateByMasterSelf()"),
				array( "GET: 	/user/groupbuyingmaster/list/<pageNo:\d+>/<pageSize:\d+>", 					"GroupBuyingMasterUserController", "getList(<pageNo>,<pageSize>)"),
				array( "POST: 	/user/groupbuyingmaster/self/activity",										"GroupBuyingActivityController", "createByMasterSelf()"),
				array( "GET: 	/user/groupbuyingmaster/self/activity/<id:\d+>",										"GroupBuyingActivityController", "getByMasterSelf(<id>)"),
				array( "GET: 	/user/groupbuyingmaster/self/product/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",	"ProductController", "searchByMasterSelf(<pageNo>,<pageSize>,<querystring>)"),
				array( "GET: 	/user/groupbuyingmaster/self/activity/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>","GroupBuyingActivityController", "searchByMasterSelf(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/user/groupbuyingmaster/self/order/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",	"OrderController", "searchByMasterSelf(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/user/groupbuyingmaster/self/order/spec/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",	"OrderController", "searchSpecByMasterSelf(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/user/groupbuyingmaster/self/return/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",	"ReturnController", "searchByMasterSelf(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/user/groupbuyingmaster/self/return/spec/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",	"ReturnController", "searchSpecByMasterSelf(<pageNo>,<pageSize>,<querystring>)"),

				array( "POST: 	/user/logout", 																"UserController", "logout(<userType>)"),
				array( "POST: 	/user/<userType:\w+>/login", 												"UserController", "login(<userType>)"),
				// array( "POST: 	/user/<userType:\w+>/<id:\d+>/group/permission",							"UserController", "appendGroupPermission(<id>)"),
				// array( "POST: 	/user/<userType:\w+>/<id:\d+>/person/permission",							"UserController", "appendPersonPermission(<id>)"),
				
				array( "POST: 	/user/<userType:\w+>/register",												"UserController", "register(<userType>)"),
				array( "GET: 	/user/<userType:\w+>/permission/<userId:\d+>", 								"UserController", "getPermissionByUserId(<userType>,<userId>)"),
				array( "GET: 	/user/self", 																"UserController", "getSelf()"),
				array( "GET: 	/user/self/permission", 													"UserController", "getSelfPermission()"),
				array( "GET: 	/user/<userType:\w+>/list/<pageNo:\d+>/<pageSize:\d+>", 					"UserController", "getList(<userType>,<pageNo>,<pageSize>)"),
				array( "PUT: 	/user/self", 																"UserController", "updateSelf()"),
                array( "PUT: 	/user/updataPassword", 																"UserController", "updataPassword()"),

                // ConsumerUser
                array( "GET: 	/consumeruser/<pageNo:\d+>/<pageSize:\d+>", 																"ConsumerUserController", "getConsumer(<pageNo>,<pageSize>)"),
                array( "GET: 	/consumeruser/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>", 																"ConsumerUserController", "getConsumerByKey(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/consumeruser/<consumerId:\d+>", 																"ConsumerUserController", "getConsumerById(<consumerId>)"),
                array( "PUT: 	/consumeruser/consignee", 																"ConsumerUserController", "updateConsignee()"),
                array( "POST: 	/consumeruser/forget", 																"ConsumerUserController", "getNewPassword()"),
                array( "POST: 	/consumeruser/checkEmail", 																"ConsumerUserController", "checkUserEmail()"),
                array( "PUT: 	/consumeruser/personal", 																"ConsumerUserController", "updatePersonal()"),


                // GroupBuyingActivity Section
                array( "GET: 	/activity/groupbuying/<id:\d+>/buyinfo",								"GroupBuyingActivityController", "getBuyInfo(<id>)"),
                array( "PUT: 	/activity/groupbuying/<id:\d+>/note", 									"GroupBuyingActivityController", "updateNote(<id>)"),
                array( "PUT: 	/activity/groupbuying/list/state", 	    								"GroupBuyingActivityController", "updateStateByIds()"),
                array( "GET: 	/activity/groupbuying/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>","GroupBuyingActivityController", "searchByAdmin(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/activity/groupbuying/search/client/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>","GroupBuyingActivityController", "searchByClient(<pageNo>,<pageSize>,<querystring>)"),

                // Activity Section
                array( "GET: 	/activity/general/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>","GeneralActivityController", "searchByAdmin(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/activity/general/search/client/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>","GeneralActivityController", "searchByClient(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/activity/general/<id:\d+>/buyinfo",                                                        "GeneralActivityController", "getBuyInfo(<id>)"),
                array( "PUT: 	/activity/general/<id:\d+>",                                                     "GeneralActivityController", "update(<id>)"),
                array( "POST: 	/activity/general",                                                     "GeneralActivityController", "create()"),
                array( "GET: 	/activity/general/<id:\d+>/relation/product/<pageNo:\d+>/<pageSize:\d+>",                           "GeneralActivityController", "getRelationProductById(<id>,<pageNo>,<pageSize>)"),
                array( "POST: 	/activity/general/<id:\d+>/relation/product",                           "GeneralActivityController", "appendRelationProduct(<id>)"),
                array( "DELETE: /activity/general/<id:\d+>/relation/product",                           "GeneralActivityController", "removeRelationProduct(<id>)"),

				// Return Section
                array( "GET: 	/return/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>", 			            "ReturnController", "search(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/return/spec/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>", 			    "ReturnController", "searchSpec(<pageNo>,<pageSize>,<querystring>)"),
                array( "PUT: 	/return/groupbuying/<id:\d+>", 														"ReturnController", "update(<id>)"),
                array( "PUT: 	/return/list/state", 														    "ReturnController", "updateStateByIds()"),
                array( "PUT: 	/return/<id:\d+>", 														        "ReturnController", "update(<id>)"),
                array( "POST: 	/return/groupbuying/user", 														"ReturnController", "createByBuyer()"),
                array( "POST: 	/return/groupbuying/consumer", 														"ReturnController", "createByConsumer()"),
                array( "POST: 	/return/complete/preview", 														            "ReturnController", "previewComplete()"),
                array( "POST: 	/return/complete", 														                    "ReturnController", "complete()"),
                array( "PUT: 	/return/remark/<id:\d+>", 														            "ReturnController", "ckangeRemark(<id>)"),

				// UserGroup Section
				array( "GET: 	/group/platformuser/<id:\d+>",												"UserGroupController", "get(<id>)"),
				array( "PUT: 	/group/platformuser/<id:\d+>",												"UserGroupController", "update(<id>)"),
				array( "POST: 	/group/platformuser/<id:\d+>/permission", 									"UserGroupController", "appendPermission(<id>)"),
				array( "POST: 	/group/platformuser", 														"UserGroupController", "create()"),
				array( "GET: 	/group/platformuser/list/<pageNo:\d+>/<pageSize:\d+>",						"UserGroupController", "getList(<pageNo>,<pageSize>)"),
				array( "GET: 	/group/platformuser/permissionset/list/<pageNo:\d+>/<pageSize:\d+>",		"UserGroupController", "getPermissionSetList(<pageNo>,<pageSize>)"),
				array( "GET: 	/group/platformuser/<id:\d+>/permission/list/<pageNo:\d+>/<pageSize:\d+>",	"UserGroupController", "getPermissionList(<id>,<pageNo>,<pageSize>)"),
				array( "GET: 	/group/platformuser/<id:\d+>/user/list/<pageNo:\d+>/<pageSize:\d+>",		"UserGroupController", "getUserList(<id>,<pageNo>,<pageSize>)"),								
				array( "PUT: 	/group/platformuser/<groupId:\d+>/user/<userId:\d+>", 						"UserGroupController", "updateUser(<groupId>,<userId>)"),
                array( "PUT: 	/group/platformuser/updata/<groupId:\d+>", 									"UserGroupController", "updateGroupName(<groupId>)"),
				array( "DELETE: /group/platformuser/<id:\d+>", 												"UserGroupController", "remove(<id>)"),

                // Order Section
                array( "POST: 	/order/<activity:\w+>/<type:[\w\-]+>", 													"OrderController", "createByActivity(<activity>,<type>)"),
                array( "POST: 	/order/<activity:\w+>/notify/<type:[\w\-]+>", 											"OrderController", "notifyByActivity(<activity>,<type>)"),
                array( "POST: 	/order/preview/<activity:\w+>/<type:[\w\-]+>", 											"OrderController", "createPreview(<activity>,<type>)"),
				array( "GET: 	/order/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>", 			                "OrderController", "search(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/order/search/consumer/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>", 			        "OrderController", "searchByConsumer(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/order/search/consumer/each/spec/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>", 			        "OrderController", "searchEachConsumerSpec(<pageNo>,<pageSize>,<querystring>)"),
                array( "PUT: 	/order/<id:\d+>", 														                "OrderController", "update(<id>)"),
                array( "PUT: 	/order/list/state", 														            "OrderController", "updateStateByIds(<id>)"),
                array( "PUT: 	/order/remark/<id:\d+>", 																"OrderController", "remarkChange(<id>)"),
                array( "PUT: 	/order/search/consumer/each/spec/remark/<id:\d+>", 												"OrderController", "remarkStatus(<id>)"),

                // Order spec section
                array( "GET: 	/order/spec/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>", 			"OrderController", "searchSpec(<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/order/spec/search/consumer/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>", 			"OrderController", "searchSpecByConsumer(<pageNo>,<pageSize>,<querystring>)"),
                array( "PUT: 	/order/spec/search/remark/<id:\d+>",
                 	"OrderController", "remarkSpec(<id>)"),

                //-------------------------------------------
                array( "PUT: 	/order/spec/update/product/<id:\d+>", 									"OrderController", "updateProductNumber(<id>)"),
                array( "PUT: 	/order/spec/update/delivery/<id:\d+>", 									"OrderController", "updateOrderSpec(<id>)"),
                //-------------------------------------------
                //Order delivery
                array( "PUT: 	/order/delivery/search/<pageNo:\d+>/<pageSize:\d+>",
                 	"OrderController", "deliverySpec(<pageNo>,<pageSize>)"),
                array( "PUT: 	/order/delivery/search/payType/<pageNo:\d+>/<pageSize:\d+>",
                 	"OrderController", "deliveryMaxPrice(<pageNo>,<pageSize>)"),

				// Permission Section
				array( "GET: 	/permission/list", 															"PermissionController", "getList()"),				

				// Cart Section
				array( "POST: 	/cart", 																	"CartController", "appendProduct()"),
				array( "GET: 	/cart/payment/<type:\w+>", 													"CartController", "getPayment(<type>)"),		
								
				// Product Group Section
                array( "GET: 	/group/product/search/<channel:\w+>/<type:\w+>/<pageNo:\d+>/<pageSize:\d+>",					    "ProductGroupController", "search(<channel>,<type>,<pageNo>,<pageSize>)"),
                array( "GET: 	/group/product/search/<channel:\w+>/<type:\w+>/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",	    "ProductGroupController", "search(<channel>,<type>,<pageNo>,<pageSize>,<querystring>)"),

                array( "GET: 	/group/product/list/<channel:\w+>/<type:\w+>/<pageNo:\d+>/<pageSize:\d+>",	"ProductGroupController", "getList(<channel>,<type>,<pageNo>,<pageSize>)"),
				array( "POST: 	/group/product/create/<channel:\w+>",										"ProductGroupController", "create(<channel>)"),
				array( "PUT: 	/group/product/<channel:\w+>/<id:\d+>",										"ProductGroupController", "update(<channel>,<id>)"),
				array( "DELETE: /group/product/<channel:\w+>/<id:\d+>",										"ProductGroupController", "remove(<channel>,<id>)"),
				
				// Product
                array( "POST: 	/product/<category:\w+>",													"ProductController", "create(<category>)"),
                array( "GET: 	/product/<category:\w+>/search/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>","ProductController", "searchByAdmin(<category>,<pageNo>,<pageSize>,<querystring>)"),
                array( "GET: 	/product/search/spec/<productId:\d+>/<pageNo:\d+>/<pageSize:\d+>",         "ProductController", "searchSpec(<productId>,<pageNo>,<pageSize>)"),
                array( "GET: 	/product/<category:\w+>/<id:\d+>",											"ProductController", "get(<category>,<id>)"),
				array( "PUT: 	/product/<category:\w+>/<id:\d+>",											"ProductController", "update(<category>,<id>)"),
                array( "PUT: 	/product/<category:\w+>/modifySpec",											"ProductController", "modifySpec(<category>)"),
				array( "DELETE: /product/<category:\w+>/<id:\d+>",											"ProductController", "remove(<category>,<id>)"),
				array( "PUT:    /product/<category:\w+>/remark/<id:\d+>",                                   "ProductController", "updateRemark(<category>,<id>)"),
                array( "GET:  /product/<category:\w+>/search/spec/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>","ProductController", "searchByAdminSpec(<category>,<pageNo>,<pageSize>,<querystring>)"),

				//Product Materials
				array( "POST: 	/product/materials/upload/<category:\w+>",									"ProductController", "materialsUpload(<category>)"),
				array( "POST: 	/product/materials/<materialsType:\w+>/<category:\w+>",											"ProductController", "materialsCreate(<materialsType>,<category>)"),
				array( "PUT: 	/product/materials/<category:\w+>",											"ProductController", "materialsUpdate(<category>)"),
				array( "DELETE: /product/materials/<channel:\w+>/<source:\w+>/<category:\w+>/<filename:\w+>/<fileType:\w+>",			"ProductController", "materialsRemove(<channel>,<source>,<category>,<filename>,<fileType>)"),

				//Export Excel
                array( "POST: 	/export/excel/<product:\w+>/<category:\w+>",								"ExportController", "exportExcel(<product>,<category>)"),

                //website
                array( "POST: 	/website/<position:\w+>/upload",								            "HomePageController", "upLoadHomePage(<position>)"),
                array( "DELETE: /website/<position:\w+>/<id:\d+>",											"HomePageController", "removeHomePage(<position>,<id>)"),
                array( "GET: 	/website/<position:\w+>/image/<pageNo:\d+>/<pageSize:\d+>",			   		"HomePageController", "getByBanner(<position>,<pageNo>,<pageSize>)"),
                array( "PUT:    /website/<position:\w+>/modify/<id:\d+>",                                   "HomePageController", "updateUrl(<position>,<id>)"),
                array( "GET: 	/website/<position:\w+>/image/group/<pageNo:\d+>/<pageSize:\d+>",			"HomePageController", "getByGroup(<position>,<pageNo>,<pageSize>)"),
                array( "POST: 	/website/<position:\w+>/upload/group",								        "HomePageController", "upLoadGroup(<position>)"),
                array( "GET: 	/website/<position:\w+>/image/promotion/<pageNo:\d+>/<pageSize:\d+>",					"HomePageController", "getBypromotion(<position>,<pageNo>,<pageSize>)"),

                // Mail
                array( "POST: 	/mail",								        "MailController", "send()"),

                //CategoryTags
                array( "GET: 	/tag/<categoryId:\d+>",														"CategoryTagController", "get(<categoryId>)"),
                array( "PUT: 	/tag/<category:\w+>/<id:\w+>",												"CategoryTagController", "update(<category>,<id>)"),
                array( "POST: 	/tag/upload/<category_id:\w+>",												"CategoryTagController", "uploadImage(<category_id>)"),
                array( "POST: 	/tag/insert/<category:\w+>",												                "CategoryTagController", "insertTag(<category>)"),
                array( "DELETE: /tag/delete/<category:\w+>/<id:\w+>",									    "CategoryTagController", "deleteTag(<category>,<id>)"),
                
                // OAuth service
                array( "GET: 	/oauth/user/info",      											        "OAuthController", "get()"),
                array( "POST: 	/oauth/user/login",      											        "OAuthController", "login()"),
                array( "POST: 	/oauth/user/register",      											        "OAuthController", "register()"),
                array( "GET: 	/oauth/logout",											                    "OAuthController", "logout(<serviceProvider>)"),
                array( "GET: 	/oauth/login/<serviceProvider:\w+>",											"OAuthController", "requestLogin(<serviceProvider>)"),
                array( "GET: 	/oauth/receive/<serviceProvider:\w+>",										"OAuthController", "handleLoginResponse(<serviceProvider>)"),
                array( "GET: 	/oauth/receive/<serviceProvider:\w+>/<querystring:\w+>",										"OAuthController", "handleLoginResponse(<serviceProvider>)"),
                array( "GET: 	/oauth/receive/<serviceProvider:\w+>/<code:\w+>/<querystring:\w+>",										"OAuthController", "handleLoginResponse(<serviceProvider>)"),
				array( "POST:   /oauth/user/check",   "OAuthController", "check()"),
				array( "GET: 	/oauth/user/info/mail",    "OAuthController", "getMail()"),
                // Mail
                array( "POST: 	/mail",								        "MailController", "send()"),

                // Reimburse
                array( "POST:    /reimburse/return/information",                       "ReimburseController",                                  "returnInformation()"),
                array( "GET: 	/reimburse/list/searchByAdmin/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",	"ReimburseController", "getReimburse(<pageNo>,<pageSize>,<querystring>)"),
                array( "PUT: 	/reimburse/statusOver/<id:\d+>", 												"ReimburseController", "StatusOver(<id>)"),
                array( "GET: 	/reimburse/search/spec/<pageNo:\d+>/<pageSize:\d+>/<querystring:\w+>",	"ReimburseController", "searchReimburseSpec(<pageNo>,<pageSize>,<querystring>)"),

			),
			'default' => array( "DefaultController", "getNotFound" )
		)
	));
}
catch(Exception $e) {
	echo $e->getMessage();
}

?>