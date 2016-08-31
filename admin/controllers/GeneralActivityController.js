/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs', 'message', 'datetime'], function (angular, app, createController, configs, message, datetime) {

    return app.controller("GeneralActivityController", createController(function ($scope, $http, $timeout, $routeParams) {


        $scope.relationProductSelecter = null;
        $scope.seletedRelationProduct = null;

        $scope.pageNo = 1;
        $scope.pageSize = 1;

        /**
         * Fetch record by api.
         */
        $scope.fetch = function(){
            var url = configs.api.generalActivity + '/search/' + $scope.pageNo + '/' + $scope.pageSize + '/';
            var request = {
                method: 'GET',
                url: url,
                headers: {'Content-Type': 'application/json'},
                params: $routeParams
            };

            $http(request).success(function(data, status, headers, config) {
                $scope.record = data.records[0];

                $scope.startdate.setdate($scope.record.start_date);
                $scope.enddate.setdate($scope.record.end_date);

            }).error(function(data, status, headers, config) {
                $scope.alert.show("無法找到此影音銷售");
            });
        };
        $scope.fetch();

        /**
         * Fetch record by api.
         */
        $scope.fetchRelationProduct = function(){
            var url = configs.api.generalActivity + '/' + $routeParams.id + '/relation/product/1/1000';
            var request = {
                method: 'GET',
                url: url,
                headers: {'Content-Type': 'application/json'},
                data: {}
            };

            $http(request).success(function(data, status, headers, config) {
                if(data.records.length > 0) {
                    $scope.showRemoveRelationProductButton = true;
                    $scope.relationProductName = data.records[0].productName;
                    $scope.relationProductPrice = data.records[0].price;
                }
                else {
                    $scope.showAppendRelationProductButton = true;
                }
            }).error(function(data, status, headers, config) {
                $scope.alert.show("取得加價購商品發生錯誤");
            });
        };
        $scope.fetchRelationProduct();

        /**
         * Append new relation product to the activity.
         */
        $scope.appendRelationProduct = function() {

            var url = configs.api.generalActivity + '/' + $routeParams.id + '/relation/product';
            var request = {
                method: 'POST',
                url: url,
                headers: {'Content-Type': 'application/json'},
                data: {}
            };

            if($scope.relationProductPrice) {
                request.data.relationProductPrice = $scope.relationProductPrice;
            }
            if($scope.relationProductId) {
                request.data.relationProductId = $scope.relationProductId;
            }

            $http(request).success(function(data, status, headers, config) {
                $scope.alert.show("新增加價購商品成功");
                $scope.showRemoveRelationProductButton = true;
                $scope.showAppendRelationProductButton = false;
            }).error(function(data, status, headers, config) {
                $scope.alert.show("新增加價購商品發生錯誤");
            });
        };

        /**
         * Remove relation product.
         */
        $scope.removeRelationProduct = function() {
            var url = configs.api.generalActivity + '/' + $routeParams.id + '/relation/product';
            var request = {
                method: 'DELETE',
                url: url,
                headers: {'Content-Type': 'application/json'},
                data: {}
            };

            $http(request).success(function(data, status, headers, config) {
                $scope.alert.show("移除加價購商品成功");
                $scope.showRemoveRelationProductButton = false;
                $scope.showAppendRelationProductButton = true;
                $scope.relationProductPrice = null;
                $scope.relationProductName = null;
                $scope.relationProductId = null;
            }).error(function(data, status, headers, config) {
                $scope.alert.show("移除加價購商品發生錯誤");
            });
        };

        $timeout(function() {

            /**
             * Override selected method.
             */
            $scope.relationProductList.onRowClick(function(row, field, instance) {
                if($scope.relationProductSelecter) {
                    $scope.relationProductSelecter.selected();
                }
                $scope.relationProductSelecter = instance;
                $scope.seletedRelationProduct = row;
                $scope.relationProductName = row.productName;
                $scope.relationProductId = row.id;
                instance.selected();
            });
        }, 150);

        /**
         * Clear relation product values.
         */
        $scope.clearRelationProduct = function () {
            if($scope.relationProductSelecter) {
                $scope.relationProductSelecter.selected();
            }
            $scope.relationProductSelecter = null;
            $scope.seletedRelationProduct = null;
            $scope.relationProductName = null;
            $scope.relationProductId = null;
            $scope.relationProductPrice = null;
        };

        /**
         * Handle create success.
         *
         * @param data
         */
        function editSuccess(data) {
            $scope.alert.show("更新資料成功");
        }

        /**
         * Handle edit error.
         *
         * @param status int Http status code from rest api.
         * @param data
         */
        function editError(status, data) {

            if(!(status)) {
                $scope.alert.show(message.UNDEFINE_ERROR);
            }
            else if(status == 500) {
                $scope.alert.show(message.SERVER_ERROR);
            }
            else if(status == -1) {
                $scope.alert.show(data.message);
            }
            else if(status == 400) {
                if(data.message) {
                    $scope.alert.show(data.message);
                }
                else {
                    $scope.alert.show(message.SERVER_ERROR);
                }
            }
            else if(status == 401) {
                $scope.alert.show(message.UNAUTHORIZED_ERROR);
            }
            else if(status == 403) {
                $scope.alert.show(message.PERMISSION_DENIED_ERROR);
            }
            else {
                $scope.alert.show("更新影音銷售失敗, 請重新嘗試");
            }
        }

        /**
         * Validator for create form.
         *
         * @param data
         */
        function verifyForm(data) {
            if(!$scope.startdate.getdate()) {
                throw {message: "開始日期沒有填寫"};
            }
            if(!$scope.enddate.getdate()) {
                throw {message: "結束日期沒有填寫"};
            }
            if( $scope.startdate.getdate() == $scope.enddate.getdate() ){
                throw {message: "開始時間與結束時間相等"};
            }
        }

        /**
         * Datetime object to string Y-m-d H:i:s.
         *
         * @param date
         * @param time
         * @returns {string}
         */
        function dateTimeFormat (date, time) {
            var year = "" + date.getFullYear();
            var month = "" + (date.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
            var day = "" + date.getDate(); if (day.length == 1) { day = "0" + day; }
            var hour = "" + time.getHours(); if (hour.length == 1) { hour = "0" + hour; }
            var minute = "" + time.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
            var second = "" + time.getSeconds(); if (second.length == 1) { second = "0" + second; }
            return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
        }

        /**
         * Update activity to db by REST API
         * @returns {number}
         */
        $scope.update = function() {
            try {
                verifyForm($scope);
                var url = configs.api.generalActivity + '/' + $routeParams.id;
                var request = {
                    method: 'PUT',
                    url: url,
                    headers: configs.api.headers,
                    data:  {
                        "name": $scope.record.name,
                        "price": $scope.record.price,
                        "startDate": $scope.startdate.getdate(),
                        "endDate": $scope.enddate.getdate()
                    }
                };

                $http(request).success(function(data, status, headers, config) {
                    editSuccess(data);
                }).error(function(data, status, headers, config) {
                    editError(status, data);
                });

                return 0;
            }
            catch(e) {
                var data = {message: "更新失敗, 原因 :" + e.message}
                editError(-1, data);
            }
        };

        $scope.cancel = function() {
            window.history.back();
        }

    }));
});