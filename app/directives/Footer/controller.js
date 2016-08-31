/*global define*/
'use strict';

define(['angular', 'app', 'jquery'], function (angular, app, $) {

	app.directive("commonFooter", function () {
		
		return {
			restrict: "EA",
			replace: true,
			transclude: true,
			templateUrl: app.applicationPath + "/directives/Footer/view.html",
			controller:  'FooterController'
		};
	});

	app.controller("FooterController", function ($scope, $location) {
		$scope.copyright = "Copyright My Website 2015";

		/**
         * Trace oauth variable.
         */
        $scope.$watch("oauth", function(oauth) {
            $scope.getLogin = function(){
	        	if($scope.oauth.result == "success"){
	        		$scope.loginViewShow.hide();

	        	}else{
	        		$scope.loginViewShow.show();
	        	}
	        }
        })


        
	});
});