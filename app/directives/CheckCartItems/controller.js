/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'configs', 'text!directives/CheckCartItems/view.html'], function (angular, app, $, configs, view) {

    app.directive("checkCartItems", function () {
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
            controller:function ($scope, $http, $cart ,$location) {
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
                    "payType" :'',
                    "fare" : '',
                    "deliveryProgramId" : ''
                };

                /**
                 * Get a int array for select element option.
                 * @param number
                 * @returns {Array}
                 */
                $scope.optionCount = function(can_sale_inventory) {
                    var number;
                    var array = [];

                    if(can_sale_inventory>20){
                        number = 20
                    }else{
                        number = can_sale_inventory;
                    }

                    for(var i = 0; i < number; i++) {
                        array.push( (i + 1).toString() );
                    }
                    return array;
                };

                $scope.returnHome =  function(){
                    $location.path("#!/#home");
                }

                $scope.$watch("alert", function(alert) {
                    if(alert){
                        if($scope.cart.length  == 0){
                            $scope.alert.show("購物車沒有產品", $scope.returnHome);
                        }
                    }
                });


                /**
                 * Get order preview info by rest api.
                 */
                $scope.getOrderPreview = function() {
                    $scope.order.spec = $scope.cart;
                    if($scope.cart.length  == 0){
                        $scope.alert.show("購物車沒有產品", $scope.returnHome);
                    }
                    var request = {
                        method: 'POST',
                        url: "/api/order/preview/general/neweb",
                        headers: configs.api.headers,
                        data: $scope.order
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.orderPreview = data;
                        $scope.preCart = angular.extend([], $scope.cart);
                    }).error(function(data, status, headers, config){
                        $scope.cart = angular.extend([], $scope.preCart);
                        if(data['c'] == 510){
                            $scope.alert.show("產品庫存不足, 請重新嘗試");
                        }else{
                            $scope.alert.show("發生錯誤, 請重新嘗試");
                        }

                    });
                };

                /**
                 * Delete spec from cart.
                 * @param index
                 */
                $scope.deleteSpec = function(index) {
                    if($scope.step[0]) {
                        var spec = $scope.orderPreview.spec;
                        delete spec[index];
                        $scope.orderPreview.spec = spec;
                        $scope.changeSpec();
                    }
                };

                /**
                 * Handling user change spec amount.
                 * Reset order preview.
                 */
                $scope.changeSpec = function() {
                    $scope.cart = [];
                    var spec = {};
                    for(var index in $scope.orderPreview.spec) {
                        spec = $scope.orderPreview.spec[index];
                        $scope.cart.push({
                            activity_id: spec.activity_id,
                            amount: spec.spec_amount,
                            id: spec.spec_id,
                            product_id: spec.product_id
                        });
                    }

                    $scope.getOrderPreview();
                };

                /**
                 * Listen fare .
                 */
                $scope.$watch("order", function(instance) {
                    if(instance) {
                        $scope.getOrderPreview();
                    }
                });

            }
        };
    });
});