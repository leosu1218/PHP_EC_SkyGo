/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("CookiesReaderController", function ($scope, $cookiesHelper) {
        var cookieId = "cookies";

        $cookiesHelper.register($scope, "params", cookieId, true, {
            onReady: function() {
                console.log("ready!", $scope.params);
            }
        });
        console.log($scope.params);


    });

});