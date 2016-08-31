/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'text!directives/Maintain/view.html', 'slick'], function (angular, app, $, view, slick) {

	app.directive("maintain", function () {
		return {
			restrict: "E",			
			template: view,
			scope: {				
				instance: '=?instance',
			},
			controller:function ($scope, $timeout, $window) {
				console.log("controller success");
			}
		};
	});
});