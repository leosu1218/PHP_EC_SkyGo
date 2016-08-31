/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'configs'], function (angular, app, $, configs) {

	return app.controller("ProductPageController", function ($scope, $routeParams, $http) {

        /**
         * Get activities product info by id.
         * @param id
         */
        $scope.get = function(id) {
            var request = {
                method: 'GET',
                url: configs.api.generalActivity + "/" + id + "/buyinfo",
                headers: configs.api.headers
            };

            $http(request).success(function(data, status, headers, config) {
                $scope.product = data;
            }).error(function(data, status, headers, config){
                $scope.alert.show("無法取得資料");
            });
        };

        $scope.get($routeParams.id);    
	});	
});