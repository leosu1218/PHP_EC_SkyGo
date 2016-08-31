/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/Promotion/view.html', 'configs'], function (angular, app, view, configs) {

	app.directive("promotion", function () {
		return {
			restrict: "E",			
			template: view,
			controller:  function($scope, $http, $routeParams, $location) {
                //取得廣告圖片
                var promotionApi = configs.api.website + "promotion/image/promotion/1/6";
                var promotionApiRequest = {
                    method: 'GET',
                    url: promotionApi,
                    headers: configs.api.headers
                };

                $http(promotionApiRequest).success(function(data, status, headers, config) {
                    $scope.promotionPath = configs.path.homepage + "promotion/";
                    $scope.promotions = data.records;
                }).error(function(data, status, headers, config){
                });

			},
			scope: {
                instance: "=?instance",
                api : "=?api"
			}
		};
	});

});