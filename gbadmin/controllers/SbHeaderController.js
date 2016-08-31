/*global define*/
'use strict';

define(['angular', 'app', 'metisMenu', 'message', 'configs' ], function (angular, app, metisMenu, message, configs) {

	app.directive("commonHeader", function () {
		return {
			restrict: "EA",
			replace: true,
			transclude: true,
			templateUrl: app.applicationPath + "/views/Sb-header.html",
			controller:  'HeaderController'
		};
	});

	app.controller("HeaderController", function ($scope, $location, $http) {
		
		checkLogin();
		activeMenuUI();

		// Menu items show flag.
		$scope.showItem = [];
		$scope.showAction = [];
		$scope.modal = {
			title: "訊息",
			message: "",
			buttonText: "OK"
		}

		/**
		*	User ao logout handler.
		*
		*/
		$scope.logout = function() {
			var request = {
				method: 'POST',
			 	url: configs.api.groupbuyingUser + "/logout",
			 	headers: configs.api.headers,	
			 	data: {},		 	
			}

			$http(request).success(function(data, status, headers, config){
				logoutSuccess(data, status);
			}).error(function(data, status, headers, config){				
				logoutError(status);
			});
		}

		/**
		*	Handle logout success.
		*	will redirect to login page.
		*
		*/
		function logoutSuccess(data) {					
			$scope.modal.buttonText = "確定";
			$scope.modal.message = message.LOGOUT_SUCCESS;			
			$('#messageModal').modal();		
			window.location = configs.path.gblogin;
		}

		/**
		*	Handle logout error.
		*	will refresh the page.
		*	
		*	@param status int Http status code from rest api.
		*/
		function logoutError(status) {
			$scope.modal.title = "發生錯誤";
			$scope.modal.buttonText = "確定";

			if(!(status)) {
				$scope.modal.message = message.UNDEFINE_ERROR;
			}	
			else if(status == 500) {
				$scope.modal.message = message.SERVER_ERROR;
			}						
			else {
				$scope.modal.message = message.LOGOUT_ERROR;
			}
			
			$('#messageModal').modal();			
		}

		function checkLogin() {
			var request = {
				method: 'GET',
			 	url: configs.api.groupbuyingUser + "/self",
			 	headers: configs.api.headers,			 	
			}

			$http(request).error(function(data, status, headers, config){				
				checkLoginError(status);
			});
		}

		/**
		*	Get self permission faild handler for active menu items.
		*
		*	@params status int Http statuse code for api error.
		*/
		function checkLoginError(status) {

			$scope.modal.title = "發生錯誤";
			$scope.modal.buttonText = "重新登入";

			if(!(status)) {
				$scope.modal.message = message.UNDEFINE_ERROR;
			}	
			else if(status == 401) {
				$scope.modal.message = message.UNAUTHORIZED_ERROR;
			}						
			else {
				$scope.modal.message = message.SERVER_ERROR;
			}
			
			$('#messageModal').on('hidden.bs.modal', function () {				
				window.location = configs.path.gblogin;
			})			
			$('#messageModal').modal();
		}

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
	});
	
});