
define(function (require) {
    return {
		domain: "skygo.com.tw",
		api: {
			headers: {'Content-Type': 'application/json'},
			userSelfPermission: "/api/user/self/permission",				
			logoutPlatform: "/api/user/logout",
			loginPlatform: "/api/user/platform/login",
			userSelf: "/api/user/self",
            userUpdataPassword: "/api/user/updataPassword",

			order: "/api/order",
			returned: "/api/return",
			remark: "/api/return/remark",
            returnedConsumer: "/api/return/groupbuying/consumer",

			// TODO redefine			
			userGroupList: "/api/group/platformuser/list",
            userGroupUpdata: "/api/group/platformuser/updata",
			platformUserGroup: "/api/group/platformuser",
			platformUser: "/api/user/platformuser",
			groupbuyingUser: "/api/user/groupbuyingmaster",
			productGroup: "/api/group/product",
            productSpec : "/api/product/search/spec",
			website: "/api/website/",
			groupbuyingActivity: "/api/activity/groupbuying",
            generalActivity:"/api/activity/general",
            order:"/api/order",
			materialUpload: "/api/product/materials/upload",
			material:"/api/materials",
			product:"/api/product",
			exportFile:"/api/export/excel/",
			userRemark:"/api/product/wholesale/remark",
			remarkchange:"/api/order/remark",
			consumeruser:"/api/consumeruser",
			orderDelivery:"/api/order/delivery",

			systemConfig:"/api/system/config",
			tag:"/api/tag",
            oauth: "/api/oauth",
            reimburse: "/api/reimburse"
		},
		path: {
			admin: "/admin.html",
			login: "/login.html",
			grouplist: "#!/group/list/1/100",
			userlist: "#!/user/list/1/100",
			gbActivityList: '#!/activity/list/1/100',
            generalActivity:"#!/general/activity",
            generalActivityList:"#!/general/activity/list",
			gbActivity: '#!/activity',
            productPath: '#!/product',
			gblogin: '/gblogin.html',
			gbadmin: '/gbadmin.html',
			material: "/upload/",
            homepage : "/upload/website/",
			image: "image/",
            productImage : "/upload/image/",
			report: "/reports/",
			groupbuyingActivity: '#!/groupbuying/activity',
			tagImage:"/upload/website/tag",

		},		
		state: {
			deliveryAlready: 4,
		},
	};
});