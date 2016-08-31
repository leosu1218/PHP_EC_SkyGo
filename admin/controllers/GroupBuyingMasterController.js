/*global define*/
'use strict';

define([
    'angular',
    'app',
    'createController',
    'configs',
    'message',
], function (
    angular,
    app,
    createController,
    configs,
    message,
    $) {

	return app.controller("GroupBuyingMasterController", createController(function ($scope, $http, $timeout, $routeParams) {

		$scope.disableBaseInfo = false;
		$scope.editBaseText = "修改";

		$scope.enableBaseInfo = function() {
			$scope.disableBaseInfo = !($scope.disableBaseInfo);
			$scope.editBaseText = ($scope.editBaseText == "修改") ? "完成":"修改";
		}

		$scope.disableBankInfo = true;		
		$scope.editBankText = "修改";

		$scope.enableBankInfo = function() {
			$scope.disableBankInfo = !($scope.disableBankInfo);
			$scope.editBankText = ($scope.editBankText == "修改") ? "完成":"修改";
		}

		$scope.disableGroupList = true;
		$scope.editGroupText = "修改";		

		$scope.enableGroupList = function() {
			$scope.disableGroupList = !($scope.disableGroupList);
			$scope.editGroupText = ($scope.editGroupText == "修改") ? "完成":"修改";
		}

		/**
		*	Fetch group master info by id.
		*
		*	@param id int The Master id.
		*/
		function fetch(id) {
			var request = {
				method: 'GET',
			 	url: configs.api.groupbuyingUser + "/" + id,
			 	headers: configs.api.headers,
			 	data: {},		 	
			}

			$http(request).success(function(data, status, headers, config) {

				$scope.userHasProductGroup = data.groups;
				$scope.name = data.name;
				$scope.email = data.email;
				$scope.account = data.account;
				$scope.password = "";
				$scope.bankName = data.bank_name;
				$scope.bankCode = data.bank_code;
				$scope.bankAccount = data.bank_account;
				$scope.bankAccountName = data.bank_account_name;
				$scope.disableBaseInfo = true;

			}).error(function(data, status, headers, config){				
				showError(status);
			});
		}

		$timeout(function() {

			fetch($routeParams.id);

		}, 10);

		function putIdsIntoTree(tree){
			$scope.$watch("userHasProductGroup",function(data){
				if(data){
					var productGroups = data.records;
					var ids = [];
					for(var index in productGroups){
						ids.push(productGroups[index].product_group_id);
					}
					tree.loadMutipleSelectedIds(ids);
				}
			});
		}

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
				
				putIdsIntoTree(tree);
			}
		});

		function getOldGroupIds(){
			var groups = [];
			for(var index in $scope.userHasProductGroup.records){
				if( $scope.userHasProductGroup.records[index].id ){
					groups.push($scope.userHasProductGroup.records[index].id);
				}
			}
			return groups;
		}

		function getRemoveGroup(){
			var oldGroup = getOldGroupIds();
			var newGroup = $scope.productGroupTree.getMutipleSelectedIds();

			var needRemove = [];
			for( var index in oldGroup){
				var isHas = false;
				for(var key in newGroup){
					if( oldGroup[index] == newGroup[key] ){
						isHas = true;
					}
				}
				if( !isHas ){
					needRemove.push( oldGroup[index] );
				}
			}
			return needRemove;
		}

		function getCreateGroup(){
			var oldGroup = getOldGroupIds();
			var newGroup = $scope.productGroupTree.getMutipleSelectedIds();

			var needCreate = [];
			for( var index in newGroup){
				var isHas = false;
				for(var key in oldGroup){
					if( oldGroup[key] == newGroup[index] ){
						isHas = true;
					}
				}
				if( !isHas ){
					needCreate.push( newGroup[index] );
				}
			}
			return needCreate;
		}

		function appendProductGroupIntoMaster( ids, callback ){
			var request = {
                method: 'PUT',
                url: configs.api.groupbuyingUser+"/"+$routeParams.id+"/groups",
                headers: configs.api.headers,
                data: {
                	ids:ids
                }
            };
            $http(request).success(function(data, status, headers, config){
            	callback(true);
            }).error(function(data, status, headers, config){
                callback(false);
            });
		}

		function removeProductGroupIntoMaster( id, callback ){
			var request = {
                method: 'DELETE',
                url: configs.api.groupbuyingUser+"/"+$routeParams.id+"/group/"+id,
                headers: configs.api.headers,
                data: {}
            };
            $http(request).success(function(data, status, headers, config){
            	callback(true);
            }).error(function(data, status, headers, config){
                callback(false);
            });
		}

		function getClear(){
			return {
				remove:{
					success:0,
					error:0,
					total:0
				},
				create:{
					success:0,
					error:0,
					total:0
				}
			};
		}

		var action = getClear();

		function showMessageFromCheck(argument) {
			var sumRemoveNumber = action.remove.success+action.remove.error;
			var sumCreateNumber = action.create.success+action.create.error;
			console.log("check",sumRemoveNumber,sumCreateNumber);
			if( sumRemoveNumber==action.remove.total && sumCreateNumber==action.create.total ){
				if( action.remove.error == 0 && action.create.error == 0 ){
					$scope.alert.show("團購主 更新產品群组成功!",function(){
						location.reload();
					});
				}else{
					$scope.alert.show("團購主 更新產品群组發生問題請重新操作!",function(){
						location.reload();
					});
				}
				action = getClear();
			}
		}

		function checkFinish( actionString, isSuccess ){

			if( actionString=="remove" ){
				if(isSuccess){
					action.remove.success++;
				}else{
					action.remove.error++;
				}
			}

			if( actionString=="create" ){
				if(isSuccess){
					action.create.success++;
				}else{
					action.create.error++;
				}
			}

			showMessageFromCheck();

		}

		$scope.modifyProductGroup = function(){
			var needCreateIds = getCreateGroup();
			var needRemoveIds = getRemoveGroup();
			console.log(needRemoveIds.length,needCreateIds.length);
			
			if(needCreateIds.length>0){
				action.create.total = needCreateIds.length;
				appendProductGroupIntoMaster(needCreateIds, function(isSuccess){
					checkFinish( "create", isSuccess );
				});
			}

			if(needRemoveIds.length>0){
				action.remove.total = needRemoveIds.length;
				for(var index in needRemoveIds){
					removeProductGroupIntoMaster(needRemoveIds[index], function(isSuccess){
						checkFinish( "remove", isSuccess );
					});
				}
			}

			if( needRemoveIds.length==0 && needCreateIds.length==0 ){
				$scope.alert.show("團購主 產品群组沒有變更!");
			}
		}

		/**
		*	Update master base info
		*
		*/
		$scope.saveBaseInfo = function() {
			var request = {
				method: 'PUT',
			 	url: configs.api.groupbuyingUser + "/" + $routeParams.id + "/base",
			 	headers: configs.api.headers,	
			 	data: {
					"name": $scope.name,
					"email": $scope.email,					
				},		 	
			}				

			$http(request).success(function(data, status, headers, config) {				
				$scope.alert.show(message.UPDATE_GB_MASTER_INFO_SUCCESS);
			}).error(function(data, status, headers, config){				
				showError(status);
			});

			return 0;
		}

		/**
		*	Update master banking info
		*
		*/
		$scope.saveBankInfo = function() {
			var request = {
				method: 'PUT',
			 	url: configs.api.groupbuyingUser + "/" + $routeParams.id + "/bank",
			 	headers: configs.api.headers,	
			 	data: {
					"bankName": $scope.bankName,
					"bankCode": $scope.bankCode,
					"bankAccount": $scope.bankAccount,
					"bankAccountName": $scope.bankAccountName,
				},		 	
			}				

			$http(request).success(function(data, status, headers, config) {				
				$scope.alert.show(message.UPDATE_GB_MASTER_INFO_SUCCESS);
			}).error(function(data, status, headers, config){				
				showError(status);
			});

			return 0;
		}

		/**
		*	Update master account info
		*
		*/
		$scope.saveAccountInfo = function() {
			var request = {
				method: 'PUT',
			 	url: configs.api.groupbuyingUser + "/" + $routeParams.id + "/account",
			 	headers: configs.api.headers,	
			 	data: {
					"password": $scope.password,					
				},		 	
			}				

			$http(request).success(function(data, status, headers, config) {				
				$scope.alert.show(message.UPDATE_GB_MASTER_INFO_SUCCESS);
			}).error(function(data, status, headers, config){				
				showError(status);
			});

			return 0;
		}

		$scope.back = function() {
			window.history.back();
		}

		/**
		*	Handle create error.		
		*	
		*	@param status int Http status code from rest api.
		*/
		function showError(status) {
			
			if(!(status)) {
				$scope.message = message.UNDEFINE_ERROR;
			}
			else if(status == 404) {
				//Do notthings.
			}
			else if(status == 500) {
				$scope.message = message.SERVER_ERROR;
			}	
			else if(status == 409) {
				$scope.message = message.COMMON_CONFLICT_ERROR;
			}
			else if(status == 401) {
				$scope.message = message.UNAUTHORIZED_ERROR;
			}	
			else if(status == 403) {
				$scope.message = message.PERMISSION_DENIED_ERROR;
			}			
			else {
				$scope.message = message.UNDEFINE_ERROR;
			}
			
			$scope.alert.show($scope.message)
		}

	}));	
});