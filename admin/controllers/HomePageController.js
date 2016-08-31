/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], function (angular, app, createController,configs) {

    return app.controller("HomePageController", createController(function ($scope) {
        $scope.getListUrl = configs.api.website + "banner/image";
        $scope.deleteUrl = configs.api.website + "banner/";
        $scope.uploadUrl = configs.api.website + "banner/upload";
        $scope.getImgUrl = configs.api.website + "banner/modify";
        $scope.imagePath = "homepage";

        $scope.getGroupListUrl = configs.api.website + "banner/image/group";
        $scope.uploadGroupUrl = configs.api.website + "banner/upload/group";
    }));

});