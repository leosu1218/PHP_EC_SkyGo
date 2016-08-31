/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'text!directives/CartBuySuccess/view.html'], function (angular, app, $, view) {

    app.directive("cartBuySuccess", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                step: '=?step',
                order: '=?order',
                cart: '=?cart',
                next: '=?next',
                orderPreview: '=?orderPreview'
            },
            controller:function ($scope, $cart, $cookiesHelper) {

            }
        };
    });
});