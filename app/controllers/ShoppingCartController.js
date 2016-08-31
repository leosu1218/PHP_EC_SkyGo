/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'datetime'], function(angular, app, $, helper) {

    return app.controller("ShoppingCartController", function($scope, $cart) {

        $cart.register($scope, "cart");
        $scope.step = [true, false, false, false];
        $scope.index = 0;
        $scope.order = {
            "fareId": 1,
            "activityId": "1",
            "name": "",
            "phone": "",
            "email": "",
            "address": "",
            "inventoryProcess": 1,
            "companyName": '',
            "taxID": '',
            "consumerRemark" : '',
            "payType" :''
        };

        /**
         * On user go next step event.
         * @param stepNumber
         */
        $scope.next = function(stepNumber) {
            for (var index in $scope.step) {
                $scope.step[index] = false;
            }
            $scope.index = stepNumber;
            $scope.step[stepNumber] = true;
        }

    });

});