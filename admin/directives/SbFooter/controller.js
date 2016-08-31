/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbFooter/view.html'], function (angular, app, view) {

	app.directive("sbFooter", function () {
		return {
			restrict: "E",			
			template: view,
			controller: function ($scope, $location) {
                $scope.copyright = "Copyright at www.life109.com 2015";
			}
		};
	});
});