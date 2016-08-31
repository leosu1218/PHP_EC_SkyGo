/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs'], function (angular, app, createController, message, configs) {

	return app.controller("ModifySelfPasswordController", createController(function ($scope, $http) {

		$scope.form = {
			password:'',
			newpasswordFirst:'',
			newpasswordSecond:''
		};

		$scope.validate = function( form ){
			var condition = [
				(form.password!=''),
				(form.newpasswordFirst!=''),
				(form.newpasswordSecond !=''),
				(form.newpasswordFirst == form.newpasswordSecond),
				(form.password != form.newpasswordFirst)
			];
			var result = true;
			for(var index in condition){
				if( !condition[index] ){
					result = false;
					return result;
				}
			}
			return result;
		};

     	$scope.modifyUserPassword = function() {
   			
		    if( $scope.validate($scope.form) ) {

		    	var req = {
					method: 'PUT',
				 	url: configs.api.userSelf,
					headers: configs.api.headers,
					data: {
						password:$scope.form.password,
						newpassword:$scope.form.newpasswordFirst
					}
				};
				
				$http(req).success(function(result) {			
					$scope.messages = [
					    { type: 'success', show:"true",  msg: '修改密碼成功!'}
					];				
				}).error(function(error) {								
					$scope.messages = [
					    { type: 'warning', show:"true",  msg: '請再確認輸入的密碼是否正確!' }
					];				
				});
		    }
		    else {		    	
		    	$scope.messages = [
				    { type: 'warning', show:"true",  msg: '請再確認輸入的密碼是否正確!' }
				];
		    }		    
		};

		$scope.closeAlert = function(index) {
			$scope.messages.splice(index, 1);
		};
	}));	
});