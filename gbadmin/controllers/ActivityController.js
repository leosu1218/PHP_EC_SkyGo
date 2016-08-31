/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'message', 'configs', 'datetime'], 
	function (angular, app, createController, message, configs, datetime) {

	return app.controller("ActivityController", 
		createController(function ($scope , $routeParams, $http, $timeout) {

            fetchRecord();

            $scope.abnormalStatement = function() {
                var msg = "您將回覆此活動對帳通知 [帳務異常], 我們將有專人與您連繫處理";
                var okButton = "我要申報異常";
                var cancelButton = "取消 再確認一下";
                var title = "對帳回覆";

                var ok = function () {
                    updateState("abnormalstatement");
                };

                var cancel = function() {};

                $scope.alert.confirm(msg, ok, title, okButton, cancel, cancelButton);
            };

            $scope.confirmStatement = function() {

                var msg = "您將回覆此活動對帳通知 [帳務正常], 我們將會在收到此通知後進行撥款作業";
                var okButton = "是 帳務正常";
                var cancelButton = "取消 再確認一下";
                var title = "對帳回覆";

                var ok = function () {
                    updateState("confirmedstatement");
                };

                var cancel = function() {};

                $scope.alert.confirm(msg, ok, title, okButton, cancel, cancelButton);
            };

            /**
             * Update activity statement state.
             * @param stateText string
             */
            function updateState(stateText) {
                var url = "/api/user/groupbuyingmaster/self/activity/list/state";
                var request = {
                    method: 'PUT',
                    url: url,
                    headers: {'Content-Type': 'application/json'},
                    data: {
                        ids: [$routeParams.id],
                        stateText: stateText
                    }
                };

                $http(request).success(function(data, status, headers, config) {
                    $scope.alert.show("已經成功回覆對帳請求");
                    $scope.showStatement = false;
                }).error(function(data, status, headers, config) {
                    $scope.alert.show("送出回覆失敗, 請重新嘗試");
                });
            }

            /**
             * Get record.
             */
            function fetchRecord() {
                var url = "/api/user/groupbuyingmaster/self/activity/" + $routeParams.id;
                var request = {
                    method: 'GET',
                    url: url,
                    headers: {'Content-Type': 'application/json'},
                    data: {}
                };

                $http(request).success(function(data, status, headers, config) {
                    $scope.totalSpecAmount = data.totalSpecAmount;
                    $scope.totalSpecPrice = data.totalSpecPrice;
                    $scope.wholesalePrice = data.wholesalePrice;
                    $scope.salePrice = data.totalSpecPrice - data.totalSpecAmount * data.wholesalePrice;
                    $scope.showStatement = (data.stateText == "waitingstatement");
                }).error(function(data, status, headers, config) {
                    $scope.alert.show("取得活動資訊失敗, 請重新嘗試");
                });
            }

            $scope.statementApi = "api/user/groupbuyingmaster/self/order/spec/search";
            $scope.statementSearch = {
                activityId: $routeParams.id,
                state: "completed"
            }

            $scope.orderApi = "api/user/groupbuyingmaster/self/order/search";
            $scope.orderSpecApi = "api/user/groupbuyingmaster/self/order/spec/search";
            $scope.orderSearch = {
                activityId: $routeParams.id
            };

            $scope.returnedApi = "api/user/groupbuyingmaster/self/return/search";
            $scope.returnedSpecApi = "api/user/groupbuyingmaster/self/order/spec/search";
            $scope.returnedSearch = {
                activityId: $routeParams.id
            };
		})
	);	
});