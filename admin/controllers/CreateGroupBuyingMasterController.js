/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message'], function (angular, app, createController, configs, message) {

	return app.controller("CreateGroupBuyingMasterController", createController(function ($scope, $http) {
		
		$scope.$watch("productGroupTree", function(tree){
			if(tree){
				tree.bindMutipleSelectedApplyHandler(function(node){
					return true;
				});
				tree.bindSingleSelectedApplyHandler(function(node){
					return false;
				});
				tree.isApplyAppend(false);
				tree.isApplyDelete(false);
			}
		});

		/**
		*	Handle create success.		
		*
		*/
		function createSuccess(data) {		
			$scope.modal.title = "訊息";			
			$scope.modal.buttonText = "確定";
			$scope.modal.message = message.CREATE_GROUPBUYING_USER_SUCCESS;			
			$('#groupMessageModal').on('hidden.bs.modal', function () {				
				// window.location = configs.path.userlist;
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

		$scope.create = function() {
			
			var request = {
				method: 'POST',
			 	url: configs.api.groupbuyingUser + "/register",
			 	headers: configs.api.headers,	
			 	data: {
					"name": $scope.name,
					"email": $scope.email,
					"account": $scope.account,
					"password": $scope.password,
					"bankAccount":$scope.bankAccount,
					"bankCode": $scope.bankCode,
					"bankName": $scope.bankName,
					"bankAccountName": $scope.bankAccountName,
					"productGroupIds": $scope.productGroupTree.getMutipleSelectedIds()
				},
			}				

			$http(request).success(function(data, status, headers, config){										
				createSuccess(data);
			}).error(function(data, status, headers, config){				
				createError(status);
			});

			return 0;
		}

		$scope.cancel = function() {

		}

	}));	
});