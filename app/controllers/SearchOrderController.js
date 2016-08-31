/*global define*/
'use strict';

define(['angular', 'app', 'jquery', 'slick' , 'configs'], function (angular, app, $, slick, configs) {

	return app.controller("SearchOrderController", function ($scope, $log, $q, $timeout, $http, $interval , $routeParams) {
        $scope.params = $routeParams;
        var orderApi = configs.api.order + "/search/consumer/1/30/";
        var orderRequest = {
            method: 'GET',
            url: orderApi,
            headers: configs.api.headers,
            params: $scope.params
        };

        $http(orderRequest).success(function(data, status, headers, config) {
            $scope.order = data.records;
        }).error(function(data, status, headers, config){
            $scope.alert("尚未登入");
        });


        var Api = configs.api.order + "/spec/search/consumer/1/30/";
        var Request = {
            method: 'GET',
            url: Api,
            headers: configs.api.headers,
            params: $scope.params
        };

        $http(Request).success(function(data, status, headers, config) {
            $scope.specs = data.records;
            $scope.orders = data.records[0];
            changeDate(data.records[0].create_datetime);
        }).error(function(data, status, headers, config){
            $scope.alert("尚未登入");
        });

        $scope.status = {
            list:[
                {text:"尚未付款", state:"prepared"},
                {text:"付款失敗", state:"abnormal"},
                {text:"等待出貨(付款成功)", state:"paid"},
                {text:"已出貨(未到貨)", state:"delivering"},
                {text:"鑑賞期(已到貨未滿7日)" , state:"warrantyperiod"},
                {text:"申請取消訂單", state:"applycancel"},
                {text:"已完成(超過鑑賞期)", state:"completed"},
                {text:"訂單已取消", state:"cancel"},
                {text:"訂單已退貨", state:"returned"},
                {text:"未選擇", state:"all"},
            ]
        };

        $scope.payTypes = {
            list:[
                {text:"信用卡線上刷卡", type:"neweb"},
                {text:"超商繳款", type:"MMK"},
                {text:"虛擬帳號轉帳", type:"ATM"},
                {text:"超商代收", type:"CS"}
            ]
        };

        $scope.inventory = {
            list:[
                {text:"捐贈發票", type:"1"},
                {text:"二聯式發票", type:"2"},
                {text:"三聯式發票", type:"3"}
            ]
        };
        
        /**
         * Display state text field.
         *
         * @param value
         * @returns {string}
         */
        $scope.displayState = function(value) {
            var state = "未定義(回報系統商)";
            var item = {};
            for(var index in $scope.status.list) {
                item = $scope.status.list[index];
                if(item.state == value) {
                    state = item.text;
                }
            }
            return state;
        }

        /**
         * Display state text field.
         *
         * @param value
         * @returns {string}
         */
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

        $scope.displayInventory = function(value) {   
            var item = {};
            for(var index in $scope.inventory.list) {
                item = $scope.inventory.list[index];
                if(item.type == value) {
                    $scope.valueState = item.text;
                }
            }
            return $scope.valueState;
        }

        function changeDate (date) {
            var str = date;
            $scope.newDate = str.substr(0,10);
        }  

	});	
});