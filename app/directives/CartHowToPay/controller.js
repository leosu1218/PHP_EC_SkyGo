/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'configs', 'text!directives/CartHowToPay/view.html'], function (angular, app, $, configs, view) {

    app.directive("cartHowToPay", function () {
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
            controller:function ($scope, $http, $cookiesHelper) {

                $scope.pageNo = 1;
                $scope.pageSize = 100;
                $scope.notLoaded = true;
                $scope.productData = {};
                $cookiesHelper.register($scope, "buyButtonActive", "buyButtonActive", true);
                $scope.payTypes = {
                    list:[
                        {text:"信用卡線上刷卡", type:"neweb"},
                        {text:"超商繳款", type:"MMK"},
                        {text:"虛擬帳號轉帳", type:"ATM"},
                        {text:"超商代收", type:"CS"}
                    ]
                };

                $scope.$watch("order.spec", function(spec) {
                    if(spec){
                        var productArray = [];
                        for(var key in spec){
                            productArray.push(Number(spec[key].product_id));
                        }
                        $scope.productData['productArray'] = productArray;
                        $scope.getFare();
                    }
                });

                /**
                 * Get valid activity id(ignore activity id = 0)
                 * @returns {Array}
                 */
                $scope.getActivityIds = function() {
                    var ids = [];
                    var spec = {};
                    for(var index in $scope.cart) {
                        spec = $scope.cart[index];
                        if(spec.id > 0) {
                            ids.push(spec.activity_id);
                        }
                    }

                    return ids;
                };

                $scope.$watch("step[1]", function(instance) {
                    if($scope.step[1]) {
                        var ids = $scope.getActivityIds();
                        // $scope.getFareByActivityIdList(ids);

                        $scope.productData['priceTotalPrice'] = $scope.orderPreview.product_total_price;

                        $scope.$watch("farePayType.selectedOption",function(type) {
                            if (type) {
                                $scope.productData['payTypeWay'] = type.pay_type;
                                $scope.getFarePrice(); 
                            }
                            
                        })
                        
                    }
                });

                $scope.nextStep = function() {
                    $scope.buyButtonActive = "login_button_active";
                    $scope.next('2');
                }

                $scope.displayPayType = function(value) {
                    var type = "";
                    var item = {};
                    for(var index in $scope.payTypes.list) {
                        item = $scope.payTypes.list[index];
                        if(item.type == value) {
                            type = item.text;
                        }
                    }
                    return type;
                }

                $scope.totalPrice = function(productPrice,farePrice){
                    return productPrice + Number(farePrice);
                }

                $scope.getFare = function() {
                    var request = {
                        method: 'PUT',
                        url: configs.api.orderDelivery + "/search/" + $scope.pageNo + "/" + $scope.pageSize,
                        headers: configs.api.headers,
                        data: $scope.productData,
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.farePayType = data.records;
                        $scope.farePayType.selectedOption = $scope.farePayType[0];
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("無法取得資料");
                    });
                }

                $scope.getFarePrice = function(){
                    var request = {
                        method: 'PUT',
                        url: configs.api.orderDelivery + "/search/payType/" + $scope.pageNo + "/" + $scope.pageSize,
                        headers: configs.api.headers,
                        data: $scope.productData,
                    };

                    $http(request).success(function(data, status, headers, config) {
                        $scope.farePrice = data;
                        $scope.order.payType = data.pay_type;
                        $scope.order.fareId = data.delivery_id;
                        $scope.order.fare = data.fare;
                        $scope.order.deliveryProgramId = data.delivery_id;
                    }).error(function(data, status, headers, config){
                        $scope.alert.show("無法取得資料");
                    });
                }

            }
        };
    });
});