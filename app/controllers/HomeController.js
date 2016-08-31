/*global define*/
'use strict';

define(['angular', 'app','configs'], function (angular, app,configs) {

    return app.controller("HomeController", function ($scope,$http) {
        //取得輪播圖片
        var homePageApi = configs.api.website + "banner/image/1/999";
        var homePageRequest = {
            method: 'GET',
            url: homePageApi,
            headers: configs.api.headers
        };

        $http(homePageRequest).success(function(data, status, headers, config) {
            $scope.homepagepPath = configs.path.homepage + "homepage/";
            $scope.homepages = data.records;

        }).error(function(data, status, headers, config){
            $scope.alert.show("無法取得圖片，請再次嘗試。");
        });

        $scope.recome = function($index){
            if($index == 0){
                return "active";
            }else{
                return "";
            }

        }

        $('.carousel').carousel({
            interval: 10000
        })

        if (document.body.offsetWidth > 768) {
            $('#toppadding').addClass("container");
        }else{
            $('#toppadding').addClass("container-fluid");
        }



    });
});