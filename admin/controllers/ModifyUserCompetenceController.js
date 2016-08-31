/*global define*/
'use strict';

define(['angular', 'app', 'createController'], function (angular, app, createController) {

	return app.controller("ModifyUserCompetenceController", createController(function ($scope) {

     	$scope.modifyUserCompetence = function() {
     		var message='modify_user_competence_success';
		    location.href = "#!/user/modify/" + message;
		};


	}));
	
});