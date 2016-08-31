/*global define*/
'use strict';

define(['angular', 'app'], function (angular, app) {

    return app.controller("PaymentResultController", function ($scope, $cookiesHelper, $routeParams ,$timeout , $cart) {
        $cookiesHelper.register($scope, "serial", "serial", true);
        $cart.register($scope, "cart");
        $timeout(function (){
            if($routeParams.result == 'success'){
                $scope.tradeResult = true;
                $scope.cart = [];
            }else{
                $scope.tradeResult = false;
            }
        }, 2000);
    });

});