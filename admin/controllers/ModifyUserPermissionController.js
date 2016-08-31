/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message'], function (angular, app, createController, configs, message) {

	return app.controller("ModifyUserPermissionController", createController(function ($scope, $http, $timeout, $routeParams) {		

		$scope.$watch("permissionList",function(pList){
			if( pList ){
				pList.loadByApi( configs.api.platformUserGroup+"/permissionset/list", 1, 9999 );
				getUserHasPermissionSetIds();
			}
		});

     	/**
		*	Fetch group info from server by api
		*	and bind view.
		*		
		*/
		$scope.getGroupList = function() {
	
			var request = {
				method: 'GET',
			 	url: configs.api.userGroupList + "/1/100000",
			 	headers: configs.api.headers,	
			 	data: {},		 	
			}

			$http(request).success(function(data, status, headers, config) {				
				if(data.records.length > 0) {
					$scope.groups 	= data.records;
					$scope.item 	= $scope.groups[0];
					// $scope.groupChanged($scope.item);
				}
				else {
					$scope.selectDisabled = true;
				}
			}).error(function(data, status, headers, config){				
				$scope.selectDisabled = true;
			});
		}

		/**
		*	Handle create success.		
		*
		*/
		function updateSuccess(data) {		
			$scope.modal.title = "訊息";			
			$scope.modal.buttonText = "確定";
			$scope.modal.message = message.MOVE_PLATFORM_USER_SUCCESS;			
			$('#groupMessageModal').on('hidden.bs.modal', function () {				
				window.location = "#!/group/" + $scope.item.id + "/user/list/1/50";
			})				
			$('#groupMessageModal').modal();
			// window.location = configs.path.grouplist;
		}

		/**
		*	Handle create error.		
		*	
		*	@param status int Http status code from rest api.
		*/
		function updateError(status) {
			$scope.modal.title = "發生錯誤";
			$scope.modal.buttonText = "確定";

			if(!(status)) {
				$scope.modal.message = message.UNDEFINE_ERROR;
			}	
			else if(status == 500) {
				$scope.modal.message = message.SERVER_ERROR;
			}	
			else if(status == 401) {
				$scope.modal.message = message.UNAUTHORIZED_ERROR;
			}	
			else if(status == 403) {
				$scope.modal.message = message.PERMISSION_DENIED_ERROR;
			}						
			else {
				$scope.modal.message = message.MOVE_PLATFORM_USER_ERROR;
			}
			
			$('#groupMessageModal').modal();			
		}

		/**
		*	User submit create new user in the selected group.
		*
		*/
		$scope.update = function() {
			console.log($scope.permissionList.getSelected());
			var request = {
				method: 'PUT',
			 	url: configs.api.platformUserGroup + "/" + $scope.groupId + "/user/" + $routeParams.userId,
			 	headers: configs.api.headers,	
			 	data: {
			 		groupId: $scope.groupId,
			 		permissions: $scope.permissionList.getSelected(),				 		
			 	},		 	
			}							

			$http(request).success(function(data, status, headers, config){
				// console.log(data);
				$scope.alert.show("存檔成功",function(){
					location.href = "#!/user/list";
				});

			}).error(function(data, status, headers, config){				
				//do somethings
			});
		}

		/**
		*	User cancel create new group
		*
		*/
		$scope.cancel = function() {
			location.href = "#!/user/list";
		}
	
		/**
		*	Bind event on group select change.
		*	Will refresh permission section with the group.
		*
		*	@param group The new group item that seleted.
		*/
		$scope.groupChanged = function(group) {			
			// $scope.fresh(group.id);
			$scope.groupId = group.id;
		}

		function getUserHasPermissionSetIds(){
			var request = {
				method: 'GET',
			 	url: configs.api.platformUser + "/permission/" + $routeParams.userId,
			 	headers: configs.api.headers,
			}							

			$http(request).success(function(data, status, headers, config){
				$scope.groupId = data.records.groupId;
				$scope.permissionList.setSelected(data.records.permissionSetIds);
			}).error(function(data, status, headers, config){				
				//do somethings
			});
		}
		
		$scope.$watch("groupId",function(id){
			if(id){
				for(var index in $scope.groups){
					if( $scope.groups[index]["id"]==id ){
						$scope.item = $scope.groups[index];
					}
				}
			}
		});

		// Initail groups.
		$scope.groupId = null;
		$scope.groups = null;
		$scope.getGroupList();

	}));
	
});