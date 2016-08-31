/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'text!directives/CartStateBar/view.html'], function (angular, app, $, view) {

    app.directive("cartStateBar", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                index: '=?index'
            },
            controller:function ($scope) {
                $scope.index = $scope.index || 0;
                $scope.checkBar = true;

                $scope.topStyle = [{
                    'background-color': '#dcdcdc'
                }, {}, {}, {}];

                $scope.triStyle = [{
                    'border-left': '22px solid #dcdcdc',
                    'border-top': '28px solid white',
                    'border-bottom': '26px solid white'
                }, {}, {}];

                $scope.backStyle = [{}, {}, {}, {}];

                $scope.$watch("index", function(instance) {
                    if(instance) {
                        for (var index in $scope.topStyle) {
                            $scope.topStyle[index] = {};
                            $scope.triStyle[index] = {};
                            $scope.backStyle[index] = {};
                        }

                        $scope.topStyle[$scope.index] = {
                            'background-color': '#dcdcdc'
                        };
                        $scope.triStyle[$scope.index] = {
                            'border-left': '22px solid #dcdcdc',
                            'border-top': '28px solid white',
                            'border-bottom': '26px solid white'
                        };
                        $scope.backStyle[$scope.index] = {
                            'border-left': '22px solid white',
                            'border-top': '29px solid #dcdcdc',
                            'border-bottom': '27px solid #dcdcdc'
                        };
                    }
                });

               

                $scope.phoneStyle = [{
                    'background-color': '#dcdcdc'
                }, {}, {}, {}];

                $scope.phoneTriStyle = [{
                    'border-style' : 'solid',
                    'border-width' : '15px 0 15px 10px',
                    'border-color' : 'transparent transparent transparent #dcdcdc'
                }, {},{}];

                $scope.$watch("index", function(instance) {
                    if ($scope.index == 1) {
                        $scope.checkBar = false;
                    }else {
                        $scope.checkBar = true;
                    }
                    if(instance) {
                        for (var index in $scope.topStyle) {
                            $scope.phoneStyle[index] = {};
                            $scope.phoneTriStyle[index] = {};
                        }

                        $scope.phoneStyle[$scope.index] = {
                            'background-color': '#dcdcdc'
                        };
                        $scope.phoneTriStyle[$scope.index] = {
                            'border-style' : 'solid',
                            'border-width' : '15px 0 15px 10px',
                            'border-color' : 'transparent transparent transparent #dcdcdc'
                        };
                    }
                });
            }
        };
    });
});