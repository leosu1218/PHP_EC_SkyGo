/*global define*/
'use strict';

define(['angular', 'app', 'createController'], function (angular, app, createController) {

	return app.controller("ModifyUserPasswordController", createController(function ($scope, $http) {
		
     	$scope.modifyUserPassword = function() {     	
   			var message='modify_user_password_success';
		    location.href = "#!/user/modify/" + message;
		};
	}));	
});