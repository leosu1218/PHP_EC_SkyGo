/*global define*/
'use strict';

define(['angular', 'app', 'createController', 'configs'], function (angular, app, createController,configs) {

    return app.controller("ManagementController", createController(function ($scope, $http, $routeParams) {

    	
     	$scope.orderSpec = $routeParams.querystring;
     	$scope.instance  = $scope.table;

     	/**
         * get order spec api.
         */
        var api = configs.api.order + "/spec/search/1/50/?keyword=&serial=" + $scope.orderSpec;
        var request = {
            method: 'GET',
            url: api,
            headers: configs.api.headers,
        };

        $http(request).success(function(data, status, headers, config) {
        	$scope.items = data.records;
        	// console.log($scope.items ,$scope.items.length);
        	
        	$scope.row = data.records[0];
        	

        	$scope.displayPayState($scope.row.pay_notify_datetime,$scope.row);
        	$scope.displayPayType($scope.row.payment_type);
        	$scope.displayState($scope.row.stateText,$scope.row);

        }).error(function(data, status, headers, config){
            // $scope.alert.show("回壓物流有誤，請再次嘗試。");
        });

        /**
         * Defined default search model's params.
         */
        $scope.payTypes = {
            list:[
                {text:"信用卡線上刷卡", type:"neweb"},
                {text:"超商繳款", type:"MMK"},
                {text:"虛擬帳號轉帳", type:"ATM"},
                {text:"超商代收", type:"CS"}
            ]
        };
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
        

		$scope.displayPayState = function(value,row) {
            if (value != null || row.state == 2) {
                $scope.valuePay = "已付款";
            }else {
                $scope.valuePay = "未付款";
            }
            return $scope.valuePay;
        }


        $scope.displayPayType = function(value){
        	var item = {};
            for(var index in $scope.payTypes.list) {
                item = $scope.payTypes.list[index];
                if(item.type == value) {
                    $scope.valueWhoPay = item.text;
                }
            }
            return $scope.valueWhoPay;
        }

		$scope.displayState = function(value, row) {
    		$scope.valueState = "未定義(回報系統商)";        
            var item = {};
            for(var index in $scope.status.list) {
                item = $scope.status.list[index];
                if(item.state == value) {
                    $scope.valueState = item.text;
                }
            }
            return $scope.valueState;
        }

        $scope.remarkReceiver = function(data){
            var url = configs.api.order + "/spec/search/remark/" + data.id;
            var request = {
                method: 'PUT',
                url: url,
                headers: configs.api.headers,
                data: data,
            };

            $http(request).success(function(data, status, headers, config) {
                // console.log("success");
            }).error(function(data){
                // console.log("fail");
            });
        }
    }));
});