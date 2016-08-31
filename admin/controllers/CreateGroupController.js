/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message'], function (angular, app, createController, configs, message) {

	return app.controller("CreateGroupController", createController(function ($scope, $http, $timeout) {
		
		$scope.groupName = "";

		$scope.$watch("permissionList",function(pList){
			if( pList ){
				pList.loadByApi( configs.api.platformUserGroup+"/permissionset/list", 1, 9999 );
			}
		});

		
		/**-
		*	Get seleted id records.
		*
		*	@return Array id = [11,12,45,77 ... ]
		*/
		function getSeletedIds() {
			return $scope.permissionList.getSelected();
		}

		/**
		*	Handle logout success.
		*	will redirect to login page.
		*
		*/
		function createSuccess(data) {		
			$scope.modal.title = "訊息";			
			$scope.modal.buttonText = "確定";
			$scope.modal.message = message.CREATE_PLATFORM_GRUOP_SUCCESS;			
			$('#groupMessageModal').on('hidden.bs.modal', function () {				
				window.location = configs.path.grouplist;
			})				
			$('#groupMessageModal').modal();
			// window.location = configs.path.grouplist;
		}

		/**
		*	Handle logout error.
		*	will refresh the page.
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
			else {
				$scope.modal.message = message.CREATE_PLATFORM_GRUOP_ERROR;
			}
			
			$('#groupMessageModal').modal();			
		}

		/**
		*	User submit create new group
		*
		*/
		$scope.create = function() {

			var request = {
				method: 'POST',
			 	url: configs.api.platformUserGroup,
			 	headers: configs.api.headers,	
			 	data: {
			 		name: $scope.groupName,
			 		permissions: getSeletedIds()
			 	},		 	
			}

			$http(request).success(function(data, status, headers, config){
				createSuccess(data, status);
			}).error(function(data, status, headers, config){				
				createError(status);
			});

		}

		/**
		*	User cancel create new group
		*
		*/
		$scope.cancel = function() {

		}

	}));
	
});