/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("OAuthResultController", function ($scope, $cookiesHelper, $routeParams ,$timeout) {

        $cookiesHelper.register($scope, "oauth", "oauth", true, {
            onReady: function() {
                $scope.oauth = $scope.login || {};
                $scope.oauth = {
                    action : $routeParams.action,
                    result : $routeParams.result,
                    name : $routeParams.name
                };
                window.close();
            }
        });
    });
});