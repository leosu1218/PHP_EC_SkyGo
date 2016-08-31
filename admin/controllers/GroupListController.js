/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs'], 
	function (angular, app, createController, message, configs) {

	return app.controller("GroupListController", createController(function( $scope , $routeParams, $http){

        function jumpPage(row, value){
            if(row.id == 1||row.id == 0){
                $scope.alert.show( row.name+"群組不可修改。");
            }else{
                location.href = "#!/group/"+row.id;
            }
        }

        $scope.$watch("table", function(table) {
            if(table) {
                $scope.pageSize = 10;
                $scope.table.configField([
                    {attribute: "id",         name: "id"},
                    {attribute: "name",       name: "群組名稱"},
                    {attribute: "control",    name: "控制",
                        controls: [
                            {type: "button", icon: "fa-times", click: removeAlert },
                            {type: "button", icon: "fa-gear", click: jumpPage }
                        ]
                    },
                ]);

                $scope.table.onRowClick(function(row, field, instance) {
                    if(field == 'name' && row.id !=0) {
                        groupClick(row, field, instance);
                    }
                    if(row.id == 0){
                        $scope.alert.show("others群組不可修改。");
                    }
                });
                onload();
            }
        });

        /**
         * On groupClick field clicked.
         * @param row
         * @param field
         * @param instance
         */
        function groupClick(row, field, instance) {
            $scope.modal.config({
                controls:[
                    {position:"header", type:"text",label:"更新"},
                    {
                        position        :"body",
                        type            :"input",
                        label           :"群組名稱",
                        attribute       :row.name,
                        attributeName   :"name"
                    },
                    {
                        position:"footer",
                        type:"button",
                        label:"確定",
                        target:function( data ){
                            data[ "id" ] = row.id;
                            updateGroup( data );
                        }
                    }
                ]
            });
            $scope.modal.show();
        }

        /**
         * Update Group name by rest api.
         * @param data
         */
        function updateGroup( data ) {
            var api = configs.api.userGroupUpdata + "/" + data.id;
            var request = {
                method: 'PUT',
                url: api,
                headers: configs.api.headers,
                data: data
            };

            $http(request).success(function(data, status, headers, config) {
                onload();
            }).error(function(data, status, headers, config){
                $scope.alert.show("修改群組名稱有誤，請再次嘗試。");
            });
        }

        function removeAlert(row, value) {

            if(row.id == 1||row.id == 0){
                $scope.alert.show( row.name+"群組不可修改。");
            }else{
                var message = "確認刪除群組? (群組內成員將移至未分類群組)";
                var callback = function() {
                    remove(row, value);
                };
                $scope.alert.confirm(message ,callback);
            }

        }


        /**
         * Remove the group
         * @param row
         * @param value
         */
		function remove(row, value) {
			var request = {
				method: 'DELETE',
			 	url: configs.api.platformUserGroup + "/" + row.id,
			 	headers: configs.api.headers,	
			 	data: {}
			}

			$http(request).success(function(data, status, headers, config){
                onload();
			}).error(function(data, status, headers, config){
                $scope.alert.show("刪除失敗");
			});
		}

        function onload(){
            $scope.table.loadByUrl( configs.api.userGroupList, 1, $scope.pageSize,
                function(data, status, headers, config) {
                    // Handle reload table success;
                },
                function(data, status, headers, config) {
                    console.log(data,status,headers,config);
                    $scope.alert.show("無法搜尋到資料");
                },
                {}
            );
        }

	}));	
});