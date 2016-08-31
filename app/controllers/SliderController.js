/*global define*/
'use strict';

define(['angular', 'app', 'configs'], function (angular, app, configs) {

    return app.controller("SliderController", function ($scope, $http, $timeout) {

        $scope.slider = [];
        $scope.items = [];

        $scope.addItem = function(index) {
            $scope.items.push({});
            $scope.$watch("slider[" + index + "]", function(instance) {
                if(instance) {

                    instance.configs({
                        slidesToShow: 2
                    });

                    instance.init([
                        {url: "iphone1.jpg"},
                        {url: "iphone2.jpg"},
                        {url: "iphone3.jpg"},
                        {url: "iphone1.jpg", click: function() { alert("click!") }},
                        {url: "iphone2.jpg"},
                        {url: "iphone3.jpg"},
                    ]);
                }
            });
        };

        $scope.clearAll = function () {
            $scope.items = [];
        };

    });
});