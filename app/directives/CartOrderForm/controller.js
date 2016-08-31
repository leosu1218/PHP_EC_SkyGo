/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'configs', 'text!directives/CartOrderForm/view.html'], function (angular, app, $, configs, view) {

    app.directive("cartOrderForm", function () {
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
            controller:function ($scope, $http, $cart, $cookiesHelper) {
                $cookiesHelper.register($scope, "oauth", "oauth", true);
                $cookiesHelper.register($scope, "tradeResult", "tradeResult", true);
                $cookiesHelper.register($scope, "buyButtonActive", "buyButtonActive", true);
                $cookiesHelper.register($scope, "serial", "serial", true);

                $scope.register = {
                    name: "",
                    email: "",
                    phone: "",
                    password: "",
                    checkPassword: ""
                };

                $scope.agreeRegister = true;

                /**
                 * Check order field not null
                 */
                $scope.currentRegisterField = function() {

                    if($scope.register.name.length == 0) {
                        $scope.alert.show("請填入購買人姓名");
                        return false;
                    }
                    else if($scope.register.email.length == 0) {
                        $scope.alert.show("請填入購買人信箱");
                        return false;
                    }
                    else if($scope.register.password.length == 0) {
                        $scope.alert.show("請填入密碼");
                        return false;
                    }
                    else if(!($scope.agreePolicy)) {
                        $scope.alert.show("請先勾選「同意接受109天GO服務條款」");
                        return false;
                    }
                    else if($scope.register.password != $scope.register.checkPassword) {
                        $scope.alert.show("您第一次輸入的密碼與第二次輸入的不同, 請重新確認");
                        return false;
                    }
                    else {
                        return true;
                    }
                };

                /**
                 * TODO remove duplicate code with UserLogin Directive
                 * User register new account.
                 */
                $scope.userRegister = function(next) {
                    if ($scope.currentRegisterField()) {
                        var url = configs.api.oauth + "/user/register";
                        var req = {
                            method: 'POST',
                            headers: configs.api.headers,
                            data: {
                                account: $scope.register.email,
                                password: $scope.register.password,
                                email: $scope.register.email,
                                phone: $scope.register.phone,
                                name: $scope.register.name
                            },
                            url: url
                        };
                        $http(req).success(function(data) {
                            $scope.oauth = {
                                result: "success",
                                name: data.info.name,
                                type: data.type,
                                id: data.info.id
                            };

                            if(typeof(next) == 'function') {
                                next();
                            }
                        }).error(function() {
                            $scope.alert.show("註冊失敗, 請重新嘗試");
                        });
                    }
                };

                /**
                 * Show login page.
                 */
                $scope.$watch("step[2]", function(instance) {
                    if($scope.step[2]) {
                        if($scope.oauth.result != "success") {
                            $scope.showLogin();
                        }
                    }
                });

                $scope.usePurchaser = function() {
                    if($scope.oauth.result == "success"){
                        $scope.order.name = $scope.consumeruser.name;
                        $scope.order.phone = $scope.consumeruser.phone;
                    }else{
                        $scope.order.name = $scope.register.name;
                        $scope.order.phone = $scope.register.phone;
                    }

                };

                /**
                 * Showing login modal view.
                 */
                $scope.showLogin = function() {
                    var callback = function () {};
                    $scope.loginView.show(callback,"我要直接購物");
                };

                /**
                 * Trace oauth variable.
                 */

                $scope.$watch("loginView", function(loginView) {
                   if(loginView) {
                       if($scope.oauth.result == "success") {
                           $scope.getConsumer();
                           $scope.loginView.hide();
                       }
                       $scope.$watch("oauth", function(instance) {
                           if(instance.result == "success") {
                               $scope.getConsumer();
                               $scope.loginView.hide();
                           }
                       });
                   }
                });



                /**
                 * Check order field not null
                 */
                $scope.currentOrderField = function() {

                    if($scope.order.name.length == 0) {
                        $scope.alert.show("請填入收件人姓名");
                        return false;
                    }
                    else if($scope.order.phone.length == 0) {
                        $scope.alert.show("請填入收件人電話");
                        return false;
                    }
                    else if($scope.order.address.length == 0) {
                        $scope.alert.show("請填入收件人住址");
                        return false;
                    }
                    else if($scope.order.inventoryProcess == 3){
                        if($scope.order.companyName.length == 0){
                            $scope.alert.show("請填入統編抬頭");
                            return false;
                        }else if($scope.order.taxID.length == 0){
                            $scope.alert.show("請填入統一編號");
                            return false;
                        }else{
                            return true;
                        }
                    }
                    else {
                        return true;
                    }
                };

                /**
                 * Create order by REST API.
                 */
                $scope.createOrder = function() {
                    if($scope.currentOrderField()) {

                        $scope.order.spec = $scope.cart;
                        var request = {
                            method: 'POST',
                            url: "/api/order/general/" + $scope.order.payType,
                            headers: configs.api.headers,
                            data: $scope.order
                        };

                        $http(request).success(function(data, status, headers, config) {
                            $scope.type           = data.order['payment_type'];
                            $scope.order.serial = data.order['serial'];
                            $scope.serial = data.order['serial'];
                            updateConsignee();

                            if( ($scope.type == "MMK") ||
                                ($scope.type == "ATM") ||
                                ($scope.type == "CS") ) {
                                $scope.tradeResult = "none";
                                formPost(data.payment);
                            }
                            else if($scope.type == "neweb") {
                                $scope.tradeResult = "none";
                                formPost(data.payment);
                            }
                            else {
                                $scope.alert.show("產生訂單失敗, 請重新嘗試");
                            }
                        }).error(function(data, status, headers, config){
                            $scope.alert.show("產生訂單失敗, 請重新嘗試");
                        });
                    }
                };

                $scope.getConsumer = function() {
                        var request = {
                            method: 'GET',
                            url: "/api/consumeruser/" + $scope.oauth.id,
                            headers: configs.api.headers
                        };

                        $http(request).success(function(data, status, headers, config) {
                            $scope.order.name = data.consignee_name;
                            $scope.order.phone = data.consignee_phone;
                            $scope.order.address = data.consignee_address;
                            $scope.consumeruser = data;
                        }).error(function(data, status, headers, config){
                        });
                }

                function updateConsignee(){
                    var consigneeData = { consumerId: $scope.oauth.id ,
                                 name : $scope.order.name,
                                 phone :$scope.order.phone,
                                 address : $scope.order.address
                    };

                    var request = {
                        method: 'PUT',
                        url: "/api/consumeruser/consignee",
                        headers: configs.api.headers,
                        data: consigneeData
                    };
                    $http(request).success(function(data, status, headers, config) {
                    }).error(function(data, status, headers, config){
                    });

                }

                /**
                 * POST data use form element
                 * @param data json The form field want to submit.
                 */
                function formPost(data) {
                    $scope.paymentConfirm.show(data.providerUrl, data, function(scope) {
                        scope.title             = "您已進行交易流程";
                        scope.sendButtonText    = "等待交易結果驗證中...";
                        scope.submitDisable     = true;
                        if( ($scope.type == "MMK") ||
                            ($scope.type == "ATM") ||
                            ($scope.type == "CS") ) {
                            $scope.cart = [];
                        }

                    });
                }

                /**
                 * Tracking trade result variable.
                 */
                $scope.$watch("tradeResult", function(instance) {
                    if(instance) {
                        if(instance == "success") {
                            $scope.paymentConfirm.hide();
                            $scope.tradeResult = "none";
                            $scope.cart = [];
                            $scope.next('3');
                        }
                        else if(instance == "error") {
                            $scope.paymentConfirm.hide();
                            $scope.alert.show("付款失敗, 請重新嘗試");
                        }
                        else {
                            // Do nothings.
                        }
                    }
                });

                /**
                 * Showing provision modal view.
                 */
                $scope.provisionShow = function() {
                    var html = '<div class="embed-responsive embed-responsive-16by9"><iframe sandbox="allow-same-origin allow-scripts allow-popups allow-forms" class="embed-responsive-item" src="http://tmall.109life.com/active/YAHOO/%E6%9C%83%E5%93%A1%E6%9C%8D%E5%8B%99%E4%BD%BF%E7%94%A8%E6%A2%9D%E6%AC%BE.txt"></iframe></div>';
                    var callback = null;
                    var title = "服務條款";
                    var buttonText = "確認";
                    var width = 900;

                    $scope.provision.showHtml(html, callback, title, buttonText, width);
                };

                /**
                 * On user click next step
                 */
                $scope.nextStep = function() {
                    if($scope.oauth.result == "success") {
                        $scope.createOrder();
                    }
                    else {
                        $scope.userRegister($scope.createOrder);
                    }
                };

                $("#checkMail").blur(function(){
                    $scope.leftOrderPad = {
                        "margin": "0px 0 63px"
                    }
                    if ($scope.register.email == null || $scope.register.email == "") {
                        $scope.mailNull = true;
                        $scope.mailFail = false;
                        $scope.mailSuccess = false;
                    }else{
                        var url = configs.api.consumeruser + "/checkEmail";
                        var req = {
                            method: 'POST',
                            headers: configs.api.headers,
                            data: {"account": $scope.register.email},
                            url: url
                        };
                        $http(req).success(function(data) {
                            $scope.mailFail = true;
                            $scope.mailSuccess = false;
                            $scope.mailNull = false;
                        }).error(function(data) {
                            $scope.mailSuccess = true;
                            $scope.mailFail = false;
                            $scope.mailNull = false;
                        });
                    }
                });
            }
        };
    });
});