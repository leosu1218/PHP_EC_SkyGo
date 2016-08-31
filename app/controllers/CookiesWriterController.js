/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("CookiesWriterController", function ($scope, $cookiesHelper) {
        var cookieId = "cookies";
        $cookiesHelper.register($scope, "params", cookieId);
    });

});