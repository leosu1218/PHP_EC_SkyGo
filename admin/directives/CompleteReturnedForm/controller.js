/*global define*/
'use strict';

define(['angular', 'app', 'configs', 'text!directives/CompleteReturnedForm/view.html'], function (angular, app, configs, view) {

    app.directive("completeReturnedForm", function () {
        return {
            restrict: "E",
            template: view,
            controller:  function($scope, $http, $route) {

                // defined directive params.
                $scope.api          = $scope.api || configs.api.returned + "/spec/search/1/99999/";
                $scope.spec         = {};
                $scope.returned        = {};
                $scope.showReturnedInfo = false;

                /**
                 * Change returned amount handler.
                 * @param record
                 */
                $scope.changeReturnedAmount = function(record) {
                    $scope.showReturnedInfo = false;
                    $scope.enableComputeButton = true;
                };

                // TODO implement
                /**
                 * Check spec's returned amount is valid.
                 * @param records array
                 * return bool If valid return true.
                 */
                function isValidAmount(records) {
                    return true;
                }

                /**
                 * Click preview returned price.
                 */
                $scope.clickPreviewCompleteReturned = function() {
                    if(isValidAmount($scope.spec.records)) {
                        var request = {
                            method: 'POST',
                            url: configs.api.returned + "/complete/preview",
                            headers: {'Content-Type': 'application/json'},
                            data: {
                                specs: $scope.spec.records,
                                serial: $scope.serial
                            }
                        };

                        $http(request).success(function(data, status, headers, config) {
                            console.log("preview", data);
                            $scope.returned = data;
                            $scope.showReturnedInfo = true;
                            $scope.enableComputeButton = false;
                        }).error(function(data, status, headers, config) {
                            $scope.alert.show("計算失敗請重新嘗試, 請重新嘗試");
                        });
                    }
                };

                /**
                 * Click complete returned price.
                 */
                $scope.clickCompleteReturned = function() {
                    if(isValidAmount($scope.spec.records)) {
                        var request = {
                            method: 'POST',
                            url: configs.api.returned + "/complete",
                            headers: {'Content-Type': 'application/json'},
                            data: {
                                specs: $scope.spec.records,
                                serial: $scope.serial
                            }
                        };

                        $http(request).success(function(data, status, headers, config) {
                            location.reload();
                        }).error(function(data, status, headers, config) {
                            $scope.alert.show("資料更新失敗請重新嘗試, 請重新嘗試");
                        });
                    }
                };

                /**
                 * Get spec records from server by rest api.
                 */
                $scope.fetchSpec = function() {
                    var request = {
                        method: 'GET',
                        url: $scope.api,
                        headers: {'Content-Type': 'application/json'},
                        params: {serial:$scope.serial}
                    }

                    $http(request).success(function(data, status, headers, config) {
                        if(data.recordCount > 0) {
                            $scope.spec = data;
                        }
                        else {
                            $scope.alert.show("錯誤, 訂單不存在任何內容, 請重新嘗試");
                        }
                    }).error(function(data, status, headers, config) {
                        $scope.alert.show("取得訂單內容失敗, 請重新嘗試");
                    });
                };

                $scope.fetchSpec();


            },
            scope: {
                serial: "=serial",
            },
        };
    });
});