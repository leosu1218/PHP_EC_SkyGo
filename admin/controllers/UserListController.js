/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs'], 
	function (angular, app, createController, message, configs) {

	return app.controller("UserListController", createController(function ( $scope , $routeParams, $timeout , $http) {
		
		$scope.alert = alert;		

		$timeout(function(){
			function gotoChangeGroup(row, attribute) { 
				// location.href = "#!/group/" + row.group_id + "/user/" + row.id + "/change";
				location.href = "#!/user/" + row.id;
			}

            function ChangePassword(row, attribute) {
                $scope.modal.config({
                    controls:[
                        {position:"header", type:"text",label:"更新"},
                        {
                            position        :"body",
                            type            :"input",
                            label           :"輸入密碼",
                            attribute       :"",
                            attributeName   :"password"
                        },
                        {
                            position:"footer",
                            type:"button",
                            label:"確定",
                            target:function( data ){
                                data[ "id" ] = row.id;
                                updataPassword( data );
                            }
                        }
                    ]
                });
                $scope.modal.show();
            }

			$scope.table.configField([		
				{
					attribute:"id", 
					name:"id"				
				},
				{
					attribute:"name", 
					name:"使用者"
				},		
				{
					attribute:"group_name", 
					name:"所屬群組",
					filter: function(value) {
						return value || "其他";
					}
				},
				{
					attribute:"account", 
					name:"登入帳號"
				},
				{
					attribute:"email", 
					name:"Email"
				},		
				{				
					attribute:"control", 
					name: "控制",
					controls: [
						{type: "button", icon: "fa-share-square-o", click: gotoChangeGroup },
                        {type: "button", icon: "fa-pencil", click: ChangePassword },
					]
				},
			]);

			var url = configs.api.platformUser + "/list";
			$scope.table.loadByUrl(url, 1, 10, 
				function(data, status, headers, config) {

				}, 
				function(data, status, headers, config) {
					if(status != 404) {
						$scope.alert("取得列表發生錯誤");	
					}					
				}
			);

		}, 100);

        /**
         * Updata Group name by rest api.
         * @param data
         */
        function updataPassword(data) {
            var api = configs.api.userUpdataPassword;
            var request = {
                method: 'PUT',
                url: api,
                headers: configs.api.headers,
                data: data
            };

            $http(request).success(function(data, status, headers, config) {

            }).error(function(data, status, headers, config){
                $scope.alert.show("修改群組名稱有誤，請再次嘗試。");
            });
        }

	}));
	
});