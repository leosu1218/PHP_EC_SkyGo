/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message'], function (angular, app, createController, configs, message) {

	return app.controller("CreateUserController", createController(function($scope, $http, $timeout) {

		$scope.$watch("permissionList",function(pList){
			if( pList ){
				pList.loadByApi( configs.api.platformUserGroup+"/permissionset/list", 1, 9999 );
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
					$scope.groupChanged($scope.item);
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
		function createSuccess(data) {		
			$scope.modal.title = "訊息";			
			$scope.modal.buttonText = "確定";
			$scope.modal.message = message.CREATE_PLATFORM_USER_SUCCESS;			
			$('#groupMessageModal').on('hidden.bs.modal', function () {				
				window.location = configs.path.userlist;
			})				
			$('#groupMessageModal').modal();
			// window.location = configs.path.grouplist;
		}

		/**
		*	Handle create error.		
		*	
		*	@param status int Http status code from rest api.
		*/
		function createError(status) {
			$scope.modal.title = "發生錯誤";
			$scope.modal.buttonText = "確定";

			if(!(status)) {
				$scope.modal.message = message.UNDEFINE_ERROR;
			}	
			else if(status == 500) {
				$scope.modal.message = message.SERVER_ERROR;
			}	
			else if(status == 409) {
				$scope.modal.message = message.CREATE_PLATFORM_USER_CONFLICT_ERROR;
			}
			else if(status == 401) {
				$scope.modal.message = message.UNAUTHORIZED_ERROR;
			}	
			else if(status == 403) {
				$scope.modal.message = message.PERMISSION_DENIED_ERROR;
			}	
			else if(status == 700) {
				$scope.modal.message = "密碼與密碼確認欄位輸入不同，請重新輸入。";
			}						
			else {
				$scope.modal.message = message.CREATE_PLATFORM_GRUOP_ERROR;
			}
			
			$('#groupMessageModal').modal();			
		}

		function isValidCreateData() {			
			return ($scope.password == $scope.passwordConfirm);
		}

		/**
		*	User submit create new user in the selected group.
		*
		*/
		$scope.create = function() {
		
			if(isValidCreateData()) {
				var request = {
					method: 'POST',
				 	url: configs.api.platformUser + "/register",
				 	headers: configs.api.headers,	
				 	data: {
				 		domain: configs.domain,
				 		name: $scope.name,
				 		email: $scope.email,
				 		account: $scope.account,
				 		password: $scope.password,
				 		groupId: $scope.groupId,
				 		// groupPermissions: $scope.getGroupSelected(),
				 		personPermissions: $scope.permissionList.getSelected()			 		
				 	},		 	
				}				

				$http(request).success(function(data, status, headers, config){										
					createSuccess(data);
				}).error(function(data, status, headers, config){				
					createError(status);
				});
			}
			else {
				createError(700);
			}
		}

		/**
		*	User cancel create new group
		*
		*/
		$scope.cancel = function() {
			
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
		
		// Initail groups.
		$scope.groupId = null;
		$scope.groups = [];
		$scope.getGroupList();

	}));	
});