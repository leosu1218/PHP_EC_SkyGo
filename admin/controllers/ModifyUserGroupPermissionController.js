/*global define*/
'use strict';

define(['angular', 'app', 'createController', "configs"], function (angular, app, createController, configs) {

	return app.controller("ModifyUserGroupPermissionController", createController(function ($scope, $http, $routeParams) {

		$scope.groupId = $routeParams.id;
		$scope.groupName = "";

		$scope.$watch("permissionList",function(pList){
			if( pList ){
				pList.loadByApi( configs.api.platformUserGroup+"/permissionset/list", 1, 9999 );
				getUserGroupInfo();
			}
		});

		function render(data){
			console.log(data);
			$scope.groupName = data.userGroup.name;
			$scope.permissionList.setSelected(data.permissionSetIds);
		}

		function getUserGroupInfo(){
			var request = {
				method: 'GET',
			 	url: configs.api.platformUserGroup+"/"+$scope.groupId,
			 	headers: configs.api.headers,
			};
			$http(request).success(function(data, status, headers, config){
				render(data.records);
			}).error(function(data, status, headers, config){
				//do something
			});
		}


		$scope.update = function(){
			var request = {
				method: 'PUT',
			 	url: configs.api.platformUserGroup+"/"+$scope.groupId,
			 	headers: configs.api.headers,
			 	data:{
			 		name:$scope.groupName,
			 		permissions:$scope.permissionList.getSelected()
			 	}
			};
			console.log(request);
			$http(request).success(function(data, status, headers, config){
				$scope.alert.show("更新成功",function(){
					location.href = "#!/group/list/1/100";
				});
			}).error(function(data, status, headers, config){
				//do something
			});
		}



	}));	
});