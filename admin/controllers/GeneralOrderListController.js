/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'jquery'],
    function (angular, app, createController, configs, $) {
        return app.controller("GeneralOrderListController",
            createController(function ($scope, $routeParams, $http, $location) {
                $scope.searchOrder = {
                    activityType: "general"
                }
            }));
    });