/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/SbHeader/view.html', 'metisMenu'], function (angular, app, view) {

	app.directive("sbHeader", function () {
		return {
			restrict: "E",			
			template: view,
			controller:  'SbHeaderController'
		};
	});

	app.controller("SbHeaderController", function ($scope, $location) {

        /**
		*	Active menu ui elements events and effect.
		*
		*/
		function activeMenuUI() {
		
			$scope.isActive = function (viewLocation) { 
	            return viewLocation === $location.path();
	        };

	        $(function() {
			    $('#side-menu').metisMenu();
			});
			$(function() {
			    $(window).bind("load resize", function() {
			        var topOffset = 50;
			        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
			        if (width < 768) {
			            $('div.navbar-collapse').addClass('collapse');
			            topOffset = 100; // 2-row-menu
			        } else {
			            $('div.navbar-collapse').removeClass('collapse');
			        }

			        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
			        height = height - topOffset;
			        if (height < 1) height = 1;
			        if (height > topOffset) {
			            $("#page-wrapper").css("min-height", (height) + "px");
			        }
			    });
			});
		}

		activeMenuUI();
	});
	
});