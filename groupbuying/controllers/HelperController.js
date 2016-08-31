/*global define*/
'use strict';

define(['angular', 'app', 'configs'], function (angular, app, configs) {

	return app.controller("HelperController", function ($scope, $timeout, $http, $routeParams) {

		$scope.returned = {};
		$scope.returned.orderSerial = $routeParams.orderSerial;

		

		/**
		*	User request return order.
		*
		*/
		$scope.requestReturn = function() {
			var request = {
				method: 'POST',
			 	url: '/api/return/groupbuying/user',
			 	headers: configs.api.headers,	
			 	data: $scope.returned,		 	
			}

			$http(request).success(function(data, status, headers, config) {				
				alert("已送出退貨請求");
			}).error(function(data, status, headers, config){
                if(status == 400) {
                    alert("您輸入的信箱及電話必須與訂單相符");
                }
                else if(status == 409) {
                    alert("此訂單目前無法退貨, 您必須在收到貨後七天鑑賞期內才可以進行退貨");
                }
                else if(status == 500) {
                    alert("伺服器目前無法處理您的動作, 請稍後再試");
                }
				else {
                    alert("發生錯誤, 無法判定的原因, 或是網路中斷");
                }
			});
		}
	
	});	
});

