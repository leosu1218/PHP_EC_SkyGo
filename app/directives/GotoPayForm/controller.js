/*global define*/
'use strict';

define(['angular', 'app', 'text!directives/GotoPayForm/view.html' ,  'jquery'], function (angular, app, view ,$) {

    app.directive("gotoPayForm", function () {
        return {
            restrict: "E",
            template: view,
            scope: {
                instance: '=?instance',
            },
            controller: function ($scope, $sce ,$timeout) {

                $scope.id = makeid();
                $scope.backdrop = "static";
                $scope.showButton1 = true;
                $scope.submitDisable = false;

                $scope.secUrl = function(url) {
                    return $sce.trustAsResourceUrl(url);
                };

                $scope.instance = {

                    /**
                     * Hide modal anyway.
                     */
                    hide: function () {
                        $('#' + $scope.id + '-Modal').modal('hide');
                    },

                    /**
                     *
                     * @param backdrop
                     */
                    setBackdrop: function (backdrop) {
                        $scope.backdrop = backdrop;
                    },

                    /**
                     * Show payment button
                     * @param providerUrl
                     * @param attributes
                     * @param callback
                     */
                    show: function (providerUrl, attributes, callback) {
                        $scope.submitDisable = false;
                        $scope.title = "您將進行線上交易";
                        $scope.sendButtonText = "訂單確認送出";
                        $scope.attributes = attributes;
                        $scope.providerUrl = providerUrl;

                        $('#' + $scope.id + '-Modal').unbind();

                        if (typeof(callback) != 'function') {
                            callback = function () {
                            };
                        }

                        $scope.onSubmitPayment = function () {
                            callback($scope);
                        };

                        $('#' + $scope.id + '-Modal').modal({
                            backdrop: $scope.backdrop
                        });

                        $timeout(function (){
                            if(!$scope.submitDisable) {
                                document.getElementById("myForm").submit();
                                $scope.title             = "您已進行交易流程";
                                $scope.sendButtonText    = "等待交易結果驗證中...";
                                $scope.submitDisable     = true;
                            }
                        }, 5000);
                    }
                };

                function makeid() {
                    var text = "";
                    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                    for( var i=0; i < 5; i++ )
                        text += possible.charAt(Math.floor(Math.random() * possible.length));
                    return text;
                }

            }
        };
    });
});