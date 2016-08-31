/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

	app.directive("commonFooter", function () {
		
		return {
			restrict: "EA",
			replace: true,
			transclude: true,
			templateUrl: app.commonPath + "/views/sb-footer.html",
			controller:  'FooterController'
		};
	});

	app.controller("FooterController", function ($scope, $location) {		
		$scope.copyright = "Copyright at www.life109.com 2015";
	});
	
});