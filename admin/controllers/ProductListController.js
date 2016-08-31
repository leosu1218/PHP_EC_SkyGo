/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs'], 
	function (angular, app, createController, message, configs) {

	return app.controller("ProductListController", 
		createController(function ( $scope , $routeParams, $http, $timeout ) {
            $scope.channel = 'wholesale';
            $scope.detail = function(row, value){
            	location.href = "#!/product/wholesale/"+row.id;
            }
		})
	);
	
});