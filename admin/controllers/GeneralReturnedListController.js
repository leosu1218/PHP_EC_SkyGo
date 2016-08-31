/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'jquery'],
    function (angular, app, createController, configs, $) {
        return app.controller("GeneralReturnedListController",
            createController(function ($scope, $routeParams, $http, $location) {
                $scope.searchReturned = {
                    activityType: "general"
                }
            }));
    });