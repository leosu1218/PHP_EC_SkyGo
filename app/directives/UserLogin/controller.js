/*global define*/
'use strict';

define(['angular', 'app', 'configs', 'text!directives/UserLogin/view.html'], function (angular, app, configs ,view){

    app.directive("userLogin", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=?instance',
                brin: '=?brin',
            },
            controller: function($scope, $http, $cookiesHelper, $location) {

                $scope.id = makeid();
                $scope.backdrop = "static";
                $scope.counter = 0;

                $cookiesHelper.register($scope, "buyButtonActive", "buyButtonActive", true);
                $scope.buyButtonActive = "login_button";
                $scope.showLoginbutton = $scope.brin;

                $scope.startLoginModel = true;
                $scope.startContentsModel = false;
                $scope.startRegisterModel = false;
                $scope.mailSuccess = false;
                $scope.mailFail = false;
                $scope.mailNull = false;
                $cookiesHelper.register($scope, "oauth", "oauth", true);
                $scope.register = {};

                $scope.alert = $scope.alert || {};
                $scope.alert.show = function(msg) {
                    $scope.counter++;
                    $scope.message = msg;
                    $scope.showMessage = true;
                };

                $scope.instance = {

                    /**
                     * Hide modal anyway.
                     */
                    hide: function() {
                        $scope.buyButtonActive = "login_button";
                        $('#' + $scope.id + '-Modal').modal('hide');
                    },

                    /**
                     *
                     * @param backdrop
                     */
                    setBackdrop: function(backdrop){
                    $scope.backdrop = backdrop;
                    },

                    /**
                     * Simply show message
                     * @param msg
                     * @param callback
                     * @param title
                     * @param buttonText1
                     */
                    show: function(callback, buttonText1) {

                        $scope.title = "";
                        $scope.buttonText1 = buttonText1 || "關閉視窗";
                        $scope.showButton2 = false;
                        $scope.htmlMessage = "";

                        $('#' + $scope.id + '-Modal').unbind();

                        if(typeof(callback) != 'function') {
                            callback = function() {};
                        }

                        $('#' + $scope.id + '-Modal').on('hidden.bs.modal', function () {
                            callback();
                        });

                        $scope.button1Click = function() {
                            $('#' + $scope.id + '-Modal').modal('hide');
                        };

                        $('#' + $scope.id + '-Modal').modal({
                            backdrop : $scope.backdrop
                        });
                    }
                };

                function makeid() {
                    var text = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                    for (var i = 0; i < 5; i++)
                        text += possible.charAt(Math.floor(Math.random() * possible.length));
                    return text;
                };

                /**
                 * Get error message from check post params.
                 * @returns {string} return "" when error not found.
                 */
                function errorOfRegister() {
                    if($scope.register.password != $scope.register.checkPassword) {
                        return "您第一次輸入的密碼與第二次輸入的不同, 請重新確認";
                    }
                    return "";
                };

                /**
                 * User register new account.
                 */
                $scope.userRegister = function() {

                    if ($scope.agreeRegister) {
                        var error = errorOfRegister();

                        if(error == "") {
                            var url = configs.api.oauth + "/user/register";
                            var req = {
                                method: 'POST',
                                headers: configs.api.headers,
                                data: {
                                    account: $scope.register.email,
                                    password: $scope.register.password,
                                    email: $scope.register.email,
                                    name: $scope.register.name,
                                    phone: $scope.register.phone
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
                            }).error(function() {
                                $scope.alert.show("註冊失敗, 請重新嘗試");
                            });
                        }
                        else {
                            $scope.alert.show(error);
                        }
                    }
                    else{
                        $scope.alert.show("請先勾選「同意會員服務條款內容」");
                    }
                };

                /**
                 * User login.
                 */
                $scope.userLogin = function() {
                    var url = configs.api.oauth + "/user/login";
                    var req = {
                        method: 'POST',
                        headers: configs.api.headers,
                        data: {"account": $scope.email, "password": $scope.password},
                        url: url
                    };
                    $http(req).success(function(data) {
                        $scope.oauth = {
                            result: "success",
                            name: data.info.name,
                            type: data.type,
                            id: data.info.id
                        };
                    }).error(function() {
                        $scope.alert.show("帳號或密碼錯誤, 請重新嘗試");
                    });
                };

                $scope.buyDirectly = function(flag) {
                    if(flag == 'login_button_active') {
                        $scope.instance.hide();
                    }
                };
                
                /**
                 * change page.
                 */
                $scope.startRegister = function(){
                    $scope.startLoginModel = false;
                    $scope.startRegisterModel = true;
                    $scope.startContentsModel = true;     
                    $scope.widthStyle = {
                        "width" : "425px",
                        "max-width" : "95%"
                    };
                }
                $scope.returnRegister = function(){
                    $scope.startLoginModel = true;
                    $scope.startRegisterModel = false;
                    $scope.startContentsModel = false;
                    $scope.widthStyle = {};
                }
                $scope.startContents = function(){
                    $scope.startUserContentsModel = true;
                    $scope.startContentsModel = true;
                    $scope.startRegisterModel = false;
                }
                $scope.returnContents = function(){
                    $scope.startUserContentsModel = false;
                    $scope.startContentsModel = true;
                    $scope.startRegisterModel = true;
                }
                $scope.startForget = function(){
                    $scope.startLoginModel = false;
                    $scope.startForgetModel = true;   
                }
                $scope.returnForget = function(){
                    $scope.startLoginModel = true;
                    $scope.startForgetModel = false;
                }

                /**
                 * use mail to get new password.
                 */
                $scope.getNewPassword = function(){
                    var url = configs.api.consumeruser + "/forget";
                    var req = {
                        method: 'POST',
                        headers: configs.api.headers,
                        data: {"account": $scope.forgetMail},
                        url: url
                    };
                    $http(req).success(function(data) {
                        alert("信件已寄出成功");
                        $scope.instance.hide();
                    }).error(function() {
                        alert("信箱輸入錯誤,請重新確認");
                    });
                };

                $("#mailCheck").blur(function(){
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