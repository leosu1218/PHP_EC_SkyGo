/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs'], 
	function (angular, app, createController, message, configs) {

	return app.controller("GroupBuyingMasterListController", createController(function( $scope , $routeParams, $http, $timeout){		

		$scope.alert = alert;


		$timeout(function(){

			function viewItem(row, attribute) { 				
				location.href = "#!/groupbuying/master/" + row["id"];
			}

			$scope.table.configField([		
				{
					attribute:"id", 
					name:"id"				
				},
				{
					attribute:"name", 
					name:"姓名"
				},		
				{
					attribute:"account", 
					name:"帳號"
				},		
				{
					attribute:"email", 
					name:"信箱"
				},		
				{				
					attribute:"control", 
					name: "控制",
					controls: [
						{type: "button", icon: "fa-edit", click: viewItem}
					]
				},				
			]);

			$scope.table.loadByUrl(configs.api.groupbuyingUser + "/list", $routeParams.pageNo, $routeParams.pageSize, 
				function(data, status, headers, config) {
					// TODO
				}, 
				function(data, status, headers, config) {
					if(status == 404) {
						$scope.alert("沒有任何團購主");
					}
					else {
						$scope.alert("發生錯誤, 無法取得列表");
					}
				}
			);
		}, 100)

	}));	
});