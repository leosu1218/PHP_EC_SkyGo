/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'],
    function (angular, app, createController, configs) {
        return app.controller("ConsumerOrderListController",
            createController(function ($scope, $routeParams, $http, $location) {
                $scope.searchOrder = {
                    activityType: "general",
                    consumerId: $routeParams.id
                }

            }));
    });