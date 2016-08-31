/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], function (angular, app, createController,configs) {

    return app.controller("AdvertisementController", createController(function ($scope) {
        $scope.getListUrl = configs.api.website + "promotion/image/promotion";
        $scope.deleteUrl = configs.api.website + "promotion/";
        $scope.uploadUrl = configs.api.website + "promotion/upload";
        $scope.getImgUrl = configs.api.website + "promotion/modify";
        $scope.imagePath = "promotion";



    }));

});